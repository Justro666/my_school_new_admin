<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link, usePage } from "@inertiajs/inertia-vue3";
import NavBar from "../Components/NavBar.vue";
import Header from "../Components/Header.vue";
import { ref, provide } from "vue";
import moment from "moment";
import PieChart from "../Components/PieChart.vue";

const props = defineProps({
    classdata: {
        type: Object,
    },
    students: {
        type: Object,
    },
    recentActions: {
        type: Object,
    },
    regionDatas: {
        type: Array,
    },
});

const ranColor = [];
 

const randomColor = () => {
  for (let index = 0; index <= 15; index++) 
    ranColor.push("#" + Math.floor(Math.random()*16777215).toString(16));
}

randomColor();

console.log(ranColor);


// console.log(props.regionDatas);

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
const beforeaftercalculate = (starttime, endtime, classdate) => {
    var today = new Date();
    var hour = today.getHours();
    var starthour = starttime.slice(0, 2);
    var endhour = endtime.slice(0, 2);

    var day = today.getDay();

    if (classdate != undefined) {
        if (classdate[day] == 1) {
            if (starthour != "" || endhour != "") {
                if (hour < starthour) {
                    return "Before " + (starthour - hour) + " hours";
                }
                if (hour >= starthour && hour <= endhour) {
                    return "Live";
                }
                if (hour > endhour) {
                    return "After " + (endhour - hour + 12) + " hours";
                }
            }
        } else {
            return "Not Today";
        }
    }
};

const bafcolor = (start, end, classdata) => {
    var bafcd = beforeaftercalculate(start, end, classdata);
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
            case "N":
                return "aftercolor";
                break;
        }
    }
};

let allstudent = 0;

for (let i = 0; i < props.students.length; i++) {
    allstudent += +props.students[i].studentsCount;
}

// Pie Chart
let options = {
    chart: {
        type: "donut",
        foreColor: "#fff",
    },
    title: {
        text: "Regions of the Students",
        align: "left",
        style: {
            fontSize: "30px",
            fontWeight: "bold",
        },
    },
    plotOptions: {
        pie: {
            donut: {
                labels: {
                    show: true,
                    total: {
                        show: true,
                        label: "Total",
                        fontSize: "22px",
                        fontWeight: "bold",
                        color: "#fff",
                    },
                },
            },
        },
    },
    colors: ranColor,
    labels: [
        "Kachin",
        "Kayah",
        "Kayin",
        "Chin",
        "Sagaing",
        "Taninthary",
        "Bago",
        "Magwe",
        "Mandalay",
        "Mon",
        "Rakhine",
        "Yangon",
        "Shan",
        "Ayeyarwady",
        "Napyidaw",
    ],
};

let series = props.regionDatas;

window.history.forward();

let showMenu = ref(true);
provide("showMenu", showMenu);
</script>

<template>
    <Head title="Dashboard" />

    <!-------------------- Navbar&header -------------------->

    <Head title="Dashboard"></Head>
    <NavBar></NavBar>

    <Header
        headername="Dashboard"
        class="w-full pr-36 transition-all duration-500"
        :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"
    />
    <!---------------- body ----------------------->
    <section
        class="absolute flex flex-col h-auto top-32 text-white w-full overflow-x-hidden bg-primaryBackground transition-all duration-500 pr-36"
        :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"
    >
        <div class="flex flex-wrap">
            <div
                class="w-52 h-24 rounded-lg customnavcolor shadow-lg text-center mt-3 mr-5"
            >
                <div
                    class="text-5xl font-bold sm:mt-2.5 mt-1.5 customtextcolor1"
                >
                    {{ allstudent }}
                </div>
                <div class="text-white opacity-30 sm:text-base text-xs">
                    All Students
                </div>
            </div>

            <div
                v-for="student in props.students"
                :key="student"
                class="w-52 h-24 rounded-lg customnavcolor shadow-lg text-center mt-3 mr-5" 
            >
                <div
                    class="sm:text-5xl text-2xl font-bold sm:mt-2.5 mt-1.5 customtextcolor2"
                >
                    {{ student.studentsCount }}
                </div>
                <div class="text-white opacity-30 sm:text-base text-xs">
                    {{ student.c_name }}
                </div>
            </div>
        </div>

        <div class="mt-7 flex md:flex-row flex-col justify-between">
            <!------------------- Active class table --------------------------------->
            <div class="md:w-6/12 w-11/12 overflow-hidden">
                <div class="flex flex-row justify-between">
                    <h3 class="text-white">Active Class</h3>
                    <Link href="./class" class="text-white text-xs ml-1 pt-1"
                        >SEE ALL</Link
                    >
                    <!-- <div class="text-white text-xs ml-1 pt-1">SEE ALL</div> -->
                </div>
                <div
                    class="custombackgroundcolor w-full max-h-max rounded-lg ml-0.5 mt-3 pl-3 py-4"
                >
                    <table class="text-white w-11/12">
                        <thead>
                            <tr class="opacity-70 sm:text-sm customfontsize">
                                <th class="text-start sm:pl-7 pl-0">NAME</th>
                                <th>DAY</th>
                                <th>TIME</th>
                                <th>PERSON</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody class="lg:text-sm text-xs customfontsize">
                            <tr
                                class="customborder"
                                v-for="(data, index) in classdata"
                                :key="index"
                            >
                                <td
                                    class="text-start sm:pl-7 pl-0 py-1"
                                    v-if="index < 5"
                                >
                                    {{ data.c_name }}
                                </td>
                                <td class="text-center py-1" v-if="index < 5">
                                    {{ datesplit(data.c_day) }}
                                </td>
                                <td class="text-center py-1" v-if="index < 5">
                                    {{ data.c_start_time }} -
                                    {{ data.c_end_time }}
                                </td>
                                <td class="text-center py-1" v-if="index < 5">
                                    {{ data.StudenyCount }}
                                </td>
                                <td
                                    class="text-center py-1"
                                    v-if="index < 5"
                                    :class="
                                        bafcolor(
                                            data.c_start_time,
                                            data.c_end_time,
                                            data.c_day
                                        )
                                    "
                                >
                                    {{
                                        beforeaftercalculate(
                                            data.c_start_time,
                                            data.c_end_time,
                                            data.c_day
                                        )
                                    }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!--------------------- Recent Action ----------------------------->
            <div class="md:w-5/12 w-10/12 text-white">
                <div class="flex flex-row justify-between">
                    <h3 class="text-white">Recent Action</h3>
                    <Link
                        href="./recentaction"
                        class="text-white text-xs ml-1 pt-1"
                        >SEE ALL</Link
                    >
                    <!-- <div class="text-white text-xs ml-1 pt-1">SEE ALL</div> -->
                </div>
                <div
                    class="custombackgroundcolor w-full max-h-max rounded-lg mt-3 pl-6 pr-4 py-4"
                >
                    <div
                        v-for="recentAction in props.recentActions"
                        :key="recentAction"
                        class="text-sm"
                    >
                        <span class="text-yellowTextColor">{{
                            recentAction.name
                        }}</span>
                        <span>{{ " " + recentAction.event + " " }}</span>
                        <span class="text-yellowTextColor">{{
                            recentAction.url.split("/")[3]
                        }}</span>
                        <span>{{ " of " }}</span>
                        <span class="text-yellowTextColor">{{
                            recentAction.auditable_type
                                .replace(/s\\/, "/")
                                .split("/")[1]
                        }}</span>
                        <span>{{ " at " }}</span>
                        <span>{{
                            moment(recentAction.updated_at).calendar()
                        }}</span>
                        <hr class="my-2" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Regions Pie Chart -->
        <div class="mt-10 p-10">
            <div class="w-full p-10 transition-all duration-500">
                <PieChart
                    width="100%"
                    height="400"
                    :options="options"
                    :series="series"
                    class="transition-all duration-500"
                ></PieChart>
            </div>
        </div>
    </section>
</template>
<style scoped>
.customalign {
    margin-bottom: 1.3em;
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
        font-size: 0.6em;
    }
}
</style>
