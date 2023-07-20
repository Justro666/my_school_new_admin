<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import NavBar from "../Components/NavBar.vue";
import Header from "../Components/Header.vue";
import ClassTable from "../Components/ClassTable.vue";
import { ref } from "@vue/reactivity";
import { watch } from "@vue/runtime-core";
import throttle from "lodash/throttle";
import { Inertia } from "@inertiajs/inertia";
import Pagination from "../Components/Pagination.vue";
import { provide } from "vue";

var categoryid = ref([]);

const props = defineProps({
    dclass: {
        type: Array,
    },
    sorttype: {
        type: Object,
    },
    categories: {
        type: Object,
    },
    checkedcategories: {
        type: Object,
    },
});

let selectedItem = ref(props.checkedcategories);
var sorting = ref(0);
const datesplit = (data) => {
    const fullday = [];
    var arrycount = 0;

    for (let index = 0; index < 7; index++) {
        var day = data.charAt(index);
        var output = dateshow(day, index);
        if (output != null) {
            fullday[arrycount++] = output;
        }
    }
    return fullday.toString();
};
const dateshow = (data, count) => {
    switch (count) {
        case 0:
            if (data == 1) {
                return "Sun";
            }
            break;
        case 1:
            if (data == 1) {
                return "Mon";
            }
            break;
        case 2:
            if (data == 1) {
                return "Tue";
            }
            break;
        case 3:
            if (data == 1) {
                return "Wed";
            }
            break;
        case 4:
            if (data == 1) {
                return "Thu";
            }
            break;
        case 5:
            if (data == 1) {
                return "Fri";
            }
            break;
        case 6:
            if (data == 1) {
                return "Sat";
            }
            break;
    }
};
const beforeaftercalculate = (starttime, endtime) => {
    var today = new Date();
    var hour = today.getHours();
    var starthour = starttime.slice(0, 2);
    var endhour = endtime.slice(0, 2);
    if (hour < starthour) {
        if (starthour - hour <= 3) {
            return "Before " + (starthour - hour) + " hours";
        }
    }
    if (hour >= starthour && hour <= endhour) {
        return "Live";
    }
    if (hour > endhour) {
        if (hour - endhour <= 3) {
            return "After " + (hour - endhour) + " hours";
        }
    }
};

const bafcolor = (start, end) => {
    var bafcd = beforeaftercalculate(start, end);
    if (bafcd != null) {
        var bafcondintion = bafcd.slice(0, 1);
        switch (bafcondintion) {
            case "B":
                return "beforecolor";
                break;

            case "L":
                return "livecolor";
                break;

            case "A":
                return "aftercolor";
                break;
        }
    }
};

const sortingF = () => {
    let sortData = props.dclass.data;
    let result;
    if (event.target.value == 0) {
        result = sortData.sort((a, b) => {
            if (a.c_start_time > b.c_start_time) {
                return -1;
            }
            if (a.c_start_time < b.c_start_time) {
                return 1;
            }
            return 0;
        });
    } else if (event.target.value == 1) {
        result = sortData.sort((a, b) => {
            if (a.c_name < b.c_name) {
                return -1;
            }
            if (a.c_name > b.c_name) {
                return 1;
            }
            return 0;
        });
    } else if (event.target.value == 2) {
        result = sortData.sort((a, b) => a.StudentCount - b.StudentCount);
    }

    props.dclass = result;
};

watch(
    selectedItem,
    throttle(function (value) {
        Inertia.get(
            "/class",
            {
                selectedItem:
                    selectedItem.value.length == 0 ? "0" : value.join("-"),
            },
            { preserveState: true, replace: true }
        );
    }, 200)
);

window.history.forward();

let showMenu = ref(true);
provide("showMenu", showMenu);
</script>

<template>
    <!-------------------- Navbar&header -------------------->
    <NavBar active="2"> </NavBar>
    <Head title="Class"></Head>
    <Header
        class="w-full pr-36 transition-all duration-500"
        :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"
    />
    <!---------------- body ----------------------->
    <div
        class="absolute h-auto w-full pt-9 headercustomleft top-32 customblack transition-all duration-500 pr-36"
        :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"
    >
        <div
            class="text-white sm:text-sm text-xs popfont flex justify-between px-4 items-center"
        >
            <div>
                <span
                    v-for="category in categories"
                    :key="categories"
                    class="ml-2"
                >
                    <input
                        type="checkbox"
                        class="css-checkbox"
                        :id="category.id"
                        checked="checked"
                        v-model="selectedItem"
                        :value="category.id"
                    />
                    <label
                        :for="category.id"
                        class="css-label lite-gray-check sm:text-base text-xs"
                        >{{ category.c_name }}</label
                    >
                </span>
            </div>

            <div class="dopd" v-show="dclass.data.length > 0">
                <form>
                    <select
                        @change="sortingF"
                        id="sorttype"
                        class="bg-black border-white focus:ring-white text-white rounded-xl customfontsize1"
                        v-model="sorting"
                    >
                        <option value="0" class="customfontsize1">
                            By Status
                        </option>
                        <option value="1" class="customfontsize1">
                            By Name
                        </option>
                        <option value="2" class="customfontsize1">
                            By Person
                        </option>
                    </select>
                </form>
            </div>
        </div>
        <div
            v-show="dclass.data.length == 0"
            class="w-full flex justify-center text-xl text-white mt-10"
        >
            There is No Class Data
        </div>
        <div class="px-4 my-6">
            <table
                class="text-white w-full rounded-lg custombackgroundcolor mb-5"
            >
                <tr
                    v-show="dclass.data.length > 0"
                    class="opacity-70 customfontsize"
                >
                    <th class="text-start pl-5 pt-4">NAME</th>
                    <th class="pt-4">INSTRUCTOR</th>
                    <th class="pt-4">DAY</th>
                    <th class="pt-4">TIME</th>
                    <th class="pt-4">PERSON</th>
                    <th class="pt-4">STATUS</th>
                    <th class="pt-4">FEES</th>
                    <th class="pt-4">Setting</th>
                </tr>

                <tbody class="text-sm customfontsize">
                    <tr
                        class="cusborder"
                        v-for="data in dclass.data"
                        :key="data.id"
                    >
                        <td class="text-start pl-4 py-2">{{ data.c_name }}</td>
                        <td class="text-center">{{ data.i_name }}</td>
                        <td class="text-center">{{ datesplit(data.c_day) }}</td>
                        <td class="text-center">
                            {{ data.c_start_time }} - {{ data.c_end_time }}
                        </td>
                        <td class="text-center">{{ data.StudentCount }}</td>
                        <td
                            class="text-center"
                            :class="
                                bafcolor(data.c_start_time, data.c_end_time)
                            "
                        >
                            {{
                                beforeaftercalculate(
                                    data.c_start_time,
                                    data.c_end_time
                                )
                            }}
                        </td>
                        <td class="text-center">
                            {{ Number(data.c_fees).toLocaleString() }} Ks
                        </td>
                        <td class="text-center customtextcolor7 underline">
                            <a :href="route('class.view', data.id)">Edit</a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div
                class="flex flex-col space-y-7 md:flex-row w-full md:justify-between items-start md:items-center text-white"
            >
                <div class="flex justify-center items-center mb-10">
                    <Pagination :links="dclass.links"></Pagination>
                </div>
                <div
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-xl text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                >
                    <Link
                        :href="route('class.create')"
                        class="flex flex-row justify-center items-center space-x-3"
                    >
                        <img
                            src="../../../public/img/addlogo.png"
                            alt=""
                            class="w-5 h-5 pt-0.5"
                        />

                        <button type="button" id="createBtn">
                            <span>Add Class</span>
                        </button>
                    </Link>
                </div>
            </div>
            <!-- <div class="text-white">{{ props.dclass }}</div> -->
        </div>
    </div>
</template>
<style scoped>
.customalign {
    margin-bottom: 1.3em;
}
.popfont {
    font-family: poppins !important;
}
.cusborder {
    border-style: solid;
    border-bottom-width: 0.13em;
    border-color: rgba(255, 255, 255, 0.2);
}
.cusmargin {
    margin-bottom: 5.63em;
}
.beforecolor {
    color: #ffc652;
}
.livecolor {
    color: #33a02c;
}
.aftercolor {
    color: #ff6551;
}
@media screen and (max-width: 640px) {
    .customfontsize {
        font-size: 0.4em !important;
    }
    .customfontsize1 {
        font-size: 0.9em !important;
    }
}
</style>
