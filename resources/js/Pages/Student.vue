<script setup>
import NavBar from "../Components/NavBar.vue";
import Header from "../Components/Header.vue";
import Pagination from "../Components/Pagination.vue";
import { Link, Head, usePage } from "@inertiajs/inertia-vue3";
import { ref, watch, provide } from "vue";
import { Inertia } from "@inertiajs/inertia";
import throttle from "lodash/throttle";

// import { prop, value } from "dom7";

let props = defineProps({
    allStudents: Object,
    filter: Object,
    categories: Object,
    checkBox: Object,
});

let totalClass = [];

let search = ref(props.filter);

let selectedItem = ref(props.checkBox);

let cat = props.categories;

// for (let i1 = 0; i1 < props.allStufdents.data.length; i1++) {
//     totalClass.push(props.allStudents.data[i1].Class);
// }

// if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
//     console.info("This page is reloaded");
// } else {
//     console.info("This page is not reloaded");
// }

// Watching the search variable and throttling the function to 300ms.
watch(
    search,
    throttle(function (value) {
        Inertia.get(
            "/student",
            { search: value },
            { preserveState: true, replace: true }
        );
    }, 300)
);
watch(
    selectedItem,
    throttle(function (value) {
        if (selectedItem.value.length > 1 && selectedItem.value[0] == 0) {
            selectedItem.value = selectedItem.value.filter(
                (item) => item != "0"
            );
        }
        Inertia.get(
            "/student",
            {
                selectedItem:
                    selectedItem.value.length == 0 ? "0" : value.join("-"),
            },
            { preserveState: true, replace: true }
        );
    }, 0)
);
window.history.forward();

let showMenu = ref(true);
provide("showMenu", showMenu);
</script>

<template>
    <!-------------------- Navbar&header -------------------->

    <NavBar />
    <Head title="Student List"> </Head>
    <Header
        headername="Student List"
        class="w-full pr-36 transition-all duration-500"
        :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"
    />

    <!---------------- body ----------------------->
    <div
        class="absolute h-auto w-full headercustomleft top-32 customblack transition-all duration-500 pr-36"
        :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"
    >
        <!-- Radio and Search Box Div -->
        <div
            class="text-white sm:text-sm text-xs popfont flex justify-between px-5"
        >
            <div class="space-x-3">
                <span v-for="category in categories" :key="category">
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
            <!-- Search Input Box Section -->
            <div class="flex items-center">
                <label for="simple-search" class="sr-only">Search</label>
                <div class="relative w-full">
                    <div
                        class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
                    >
                        <svg
                            aria-hidden="true"
                            class="w-5 h-5 text-gray-500 dark:text-gray-400"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd"
                            ></path>
                        </svg>
                    </div>
                    <input
                        type="text"
                        id="simple-search"
                        class="border bg-elementBackground border-gray-300 text-white text-sm rounded-lg focus:ring-white focus:border-white focus:ring-1 block w-full pl-10 p-2.5"
                        placeholder="Search"
                        v-model="search"
                        required
                    />
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="px-4 my-6 w-full bg-elementBackground mr-10 rounded-xl">
            <table
                class="text-white w-full bg-elementBackground rounded-lg mb-5"
            >
                <tr
                    class="opacity-70 customfontsize"
                    v-show="allStudents.data.length > 0"
                >
                    <th class="text-start pl-5 pt-4">NAME</th>
                    <th class="pt-4">TOTAL CLASS</th>
                    <th class="pt-4">AGE</th>
                    <th class="pt-4 md:block hidden">PHONE</th>
                    <th class="pt-4">ADDRESS</th>
                    <th class="pt-4">DETAILS</th>
                    <th class="pt-4">EDIT</th>
                </tr>
                <tbody class="text-sm customfontsize">
                    <tr
                        v-show="allStudents.data.length > 0"
                        class="cusborder border-b border-slate-600"
                        v-for="(student, index) in allStudents.data"
                        :key="allStudents.data"
                    >
                        <td class="text-start pl-4 py-2">{{ student.name }}</td>
                        <td class="text-center" v-show="student.Class > 0">
                            {{ student.Class }}
                        </td>
                        <td class="text-center" v-show="student.Class == null">
                            0
                        </td>
                        <td class="text-center">{{ student.age }}</td>
                        <td class="text-center">
                            {{ student.phone }}
                        </td>
                        <td class="text-center">{{ student.address }}</td>
                        <td class="text-center text-yellowTextColor underline">
                            <Link :href="route('student.show', student.id)"
                                >View</Link
                            >
                        </td>
                        <td class="text-center text-yellowTextColor underline">
                            <Link :href="route('student.edit', student.id)"
                                >Edit</Link
                            >
                        </td>
                    </tr>
                    <tr
                        class="text-xl items-center flex justify-center mt-10"
                        v-show="allStudents.data.length < 0"
                    >
                        There is no student
                    </tr>
                </tbody>
            </table>
            <div
                class="flex justify-center items-center my-10"
                v-show="allStudents.links.length > 0"
            >
                <Pagination class="z-10" :links="allStudents.links">
                </Pagination>
            </div>
            <div class="flex flex-row w-full text-white justify-end pb-4">
                <Link
                    :href="route('student.create')"
                    class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-xl text-sm px-5 py-2.5 mr-2 mb-2"
                >
                    <div
                        :href="route('student.create')"
                        class="flex flex-row justify-center items-center"
                    >
                        <img
                            src="../../../public/img/addlogo.png"
                            alt=""
                            class="w-5 h-5 pt-0.5"
                        />

                        <button type="button" id="createBtn">
                            <span>Add Student</span>
                        </button>
                    </div>
                </Link>
            </div>
        </div>
        <!-- Footer Section -->
    </div>
</template>
