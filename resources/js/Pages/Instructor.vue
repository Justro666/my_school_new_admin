<script setup>
import NavBar from "../Components/NavBar.vue";
import Header from "../Components/Header.vue";
import Pagination from "../Components/Pagination.vue";
import { Inertia } from "@inertiajs/inertia";
import throttle from "lodash/throttle";
import { ref, watch , provide } from "vue";
import { useForm, Head } from "@inertiajs/inertia-vue3";
import { Link } from "@inertiajs/inertia-vue3";
import axios from "axios";

const props = defineProps({
    instructors: {
        type: Array,
    },
    sessionId: {
        Object,
    },
    categorys: Object,
});


let roleList = props.categorys.map((item) => item.id);

const roleCat = props.categorys.map((item) => {
    let obj = {
        roleId: item.id,
        checked: true,
    };
    return obj;

});

let searchname = ref();

watch(
    searchname,
    throttle(function (value) {

        Inertia.get(
            "/instructor",
            { searchname: value },
            { preserveState: true, replace: true }
        );
    })
);
window.history.forward();

const showMenu = ref(true);

provide('showMenu', showMenu);
console.log(props.instructors);
</script>

<template>
    <Head title="Instructor" />
    <NavBar active="5"> </NavBar>
    <Header headername="Instructor" class="w-full pr-36 transition-all duration-500" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"/>
    <div
        class="absolute h-auto w-full headercustomleft top-32 bg-primaryBackground rounded-lg mt-10 customblack transition-all duration-500 pr-36   " :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"
    >
    <!-- Search -->
        <div class="flex justify-end pb-6">
            <div class="flex items-center">
                <label for="simple-search" class="sr-only">Search</label>
                <div class=" relative w-full">
                     <div
                        class="absolute inset-y-0 left-16  flex items-center pl-3 pointer-events-none"
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
                    v-model="searchname"
                    placeholder="Search"
                    class="focus:ring-white focus:border-white bg-elementBackground text-sm rounded-xl ml-16 p-2 text-white w-64 pl-10"
                />
                </div>
            </div>
        </div>

        <div class="px-4 w-full bg-elementBackground rounded-lg">
            <div class="px-4 w-full bg-elementBackground rounded-lg">
                <p
                    class="text-center flex justify-center pt-5 text-lg text-white"
                    v-show="instructors.length == 0"
                >
                    There is no Data
                </p>
                <table
                    class="text-white w-full bg-elementBackground rounded-lg"
                    v-show="instructors.length > 0"
                >
                    <tr class="opacity-70 customfontsize">
                        <th class="text-start pt-4 pl-10">NAME</th>
                        <th class="pt-4">CLASSES</th>
                        <th class="pt-4">TOTAL STUDENT</th>
                        <th class="pt-4">CONTACT</th>
                        <th class="pt-4">ADDRESS</th>
                        <th class="pt-4">SETTING</th>
                    </tr>
                    <tbody class="text-sm customfontsize mt-3">
                        <tr
                            class="border-b"
                            v-for="instructor in instructors"
                            :key="instructor"
                        >
                            <td class="text-start pl-10 py-4">
                                {{ instructor.name }}
                            </td>
                            <td class="text-center py-4">
                                {{ instructor.class }}
                            </td>
                            <td class="text-center py-4">
                                {{ instructor.student }}
                            </td>
                            <td class="text-center py-4">
                                {{ instructor.contact }}
                            </td>
                            <td class="text-center py-4">
                                {{ instructor.address }}
                            </td>
                            <td
                                class="text-center text-yellowTextColor underline py-4"
                            >
                                <Link
                                    :href="
                                        route('instructor.edit', instructor.instId)
                                    "
                                >
                                    Edit
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="flex items-center justify-center my-5"></div>
            </div>
        </div>
    </div>
</template>

<style></style>
