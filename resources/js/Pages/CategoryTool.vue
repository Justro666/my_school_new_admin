<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";
import NavBar from "../Components/NavBar.vue";
import Header from "../Components/Header.vue";
import Toolsbar from "../Components/Toolsbar.vue";
import moment from "moment";
import { ref, provide } from "vue";

const props = defineProps({
    categories: {
        type: Object
    }
});

window.history.forward();

const showMenu = ref(true);

provide('showMenu', showMenu);
</script>

<template>
    <Head title="Category List" />

    <NavBar> </NavBar>
    <Header headername="Tools" class="w-full pr-36 transition-all duration-500" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"/>

    <div class="absolute h-auto w-full py-5 headercustomleft top-32 customblack transition-all duration-500 pr-36" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'">
        <Toolsbar active="4" />
        <div class="w-full h-auto p-8 relative bg-secondaryBackground rounded-b-xl flex flex-col items-center">
            <!-- Table Section -->
            <div class="p-4 w-full  rounded-xl">
                <table class="text-white w-full rounded-lg ">
                    <tr class="opacity-70 customfontsize">
                        <th class="text-center py-3">Name</th>
                        <th class="py-3">DESCRIPTION</th>
                        <th class="py-3">DATE</th>
                        <th class="py-3">SETTINGS</th>
                    </tr>
                    <tbody class="text-sm mt-3">

                        <tr class="border-b" v-for="result in props.categories.data" :key="result">
                            <td class="text-left py-3 text-base">{{ result.name }}</td>
                            <td class="text-center py-3 text-base">{{ result.description.substring(0, 10) + "..." }}
                            </td>
                            <td class="text-center py-3 text-base">{{ moment(result.date).calendar() }}</td>
                            <td class="text-center customtextcolor7 underline text-base">
                                <Link :href="route('categoryTool.edit', result.categoryId)" :id="result.categoryId">Edit</Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex flex-col mt-10 md:flex-row w-full px-5 items-center justify-center text-white">
                <!-- <Pagination/> -->

                <div
                    class="absolute text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-xl text-sm px-5 py-2.5 mr-2 mb-2 bottom-5 right-3 focus:outline-none">
                    <Link :href="route('categoryTool.create')" class="flex flex-row justify-center items-center space-x-3">
                    <img src="../../../public/img/addlogo.png" alt="" class="w-5 h-5 pt-0.5" />

                    <button type="button">
                        <span class="mx-1 font-bold text-base">Add</span>
                    </button>
                    </Link>
                </div>

                <!-- Hello -->
            </div>
        </div>
    </div>
</template>