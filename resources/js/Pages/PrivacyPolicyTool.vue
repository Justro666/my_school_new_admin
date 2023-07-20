<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";
import NavBar from "../Components/NavBar.vue";
import Header from "../Components/Header.vue";
import Toolsbar from "../Components/Toolsbar.vue";
import Pagination from "../Components/Pagination.vue"
import { DOMDirectiveTransforms } from "@vue/compiler-dom";
import moment from "moment";
import { ref, provide } from "vue";

const props = defineProps({
    privacypolicys: {
        type: Object
    }
});

window.history.forward();

const showMenu = ref(true);

provide('showMenu', showMenu);
</script>

<template>
    <Head title="Privacy Policy List" />

    <NavBar></NavBar>
    <Header headername="Tools" class="w-full pr-36 transition-all duration-500" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"/>

    <div class="absolute h-auto w-full overflow-x-hidden py-5 headercustomleft top-32 customblack transition-all duration-500 pr-36" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'">
        <Toolsbar active="2" />

        <div class="w-full h-auto p-8 relative bg-secondaryBackground rounded-b-xl flex flex-col items-center">

            <!-- Table Section -->
            <div class="p-4 w-full  rounded-xl">
                <table class="text-white w-full rounded-lg ">
                    <tr class="opacity-70 customfontsize">
                        <th class="text-center py-4">TITLE</th>
                        <th class="py-4">DESCRIPTION</th>
                        <th class="py-4">DATE</th>
                        <th class="py-4">CATEGORY</th>
                        <th class="py-4">PUBLISH</th>
                        <th class="py-4">SETTINGS</th>
                    </tr>
                    <tbody class="text-sm mt-3">
                        <tr class="border-b" v-for="result in props.privacypolicys.data" :key="result">
                            <!-- <td>{{ result.p_title }}</td> -->
                            <td class="text-left py-4">{{ result.title.substring(0, 15) + "..." }}</td>
                            <td class="text-center py-4">{{ result.description.substring(0, 20) + "..." }}</td>
                            <td class="text-center py-4">{{ moment(result.date).calendar() }}</td>
                            <td class="text-center py-4">{{ result.category }}</td>
                            <td class="text-center py-3 font-bold text-green-500">
                                <span v-if="result.publish == 0" class="text-green-600">Publish</span>
                                <span v-else class="text-red-600">Unpublish</span>
                            </td>
                            <td class="text-center py-4 customtextcolor7 underline">
                                <Link :href="route('privacypolicyTool.edit', result.privacyId)" :id="result.privacyId">Edit</Link>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="flex justify-center items-center my-10">
                    <Pagination class="z-10" :links="privacypolicys.links">
                    </Pagination>
                </div>
            </div>

            <div class="flex flex-col mt-10 md:flex-row w-full px-5 items-center justify-center text-white">
                <div
                    class="absolute text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-xl text-sm px-5 py-2.5 mr-2 mb-2 bottom-5 right-3 focus:outline-none">
                    <Link :href="route('privacypolicyTool.create')" class="flex flex-row justify-center items-center space-x-3">
                    <img src="../../../public/img/addlogo.png" alt="" class="w-5 h-5 pt-0.5" />

                    <button type="button">
                        <span>Add</span>
                    </button>
                    </Link>
                </div>
            </div>
        </div>


    </div>
</template>