<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";
import NavBar from "../Components/NavBar.vue";
import Header from "../Components/Header.vue";
import Toolsbar from "../Components/Toolsbar.vue";
import Pagination from "../Components/Pagination.vue";
import { ref,provide } from "vue";
import moment from "moment";

const props = defineProps({
    guides: {
        type: Object,
    },
});


console.log(props.guides);
window.history.forward();
const showMenu = ref(true);

provide('showMenu', showMenu);
</script>

<template>
<Head title="Guide Tools" />
    <NavBar active=8> </NavBar>
    <Header headername="Tools" class="w-full pr-36 transition-all duration-500" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"/>

    <div class="absolute h-auto w-full py-5 headercustomleft top-32 customblack transition-all duration-500 pr-36" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'">
        <Toolsbar active="5" />
        <div
            class="w-full h-auto p-8 relative bg-secondaryBackground rounded-b-xl flex flex-col items-center"
        >
            <!-- Table Section -->
            <div class="p-4 w-full rounded-xl">
                <table class="text-white w-full rounded-lg">
                    <tr class="opacity-70 customfontsize">
                        <th class="text-left py-3">GUIDE TITLE</th>
                        <th class="py-3">STEP</th>
                        <th class="py-3">DATE</th>
                        <th class="py-3">PUBLISH</th>
                        <th class="py-3">SETTINGS</th>
                    </tr>
                    <tbody class="text-sm mt-3">
                        <tr
                            class="border-b"
                            v-for="result in guides.data"
                            :key="result"
                        >
                            <td class="text-left py-3 text-base">
                                {{ result.title }}
                            </td>
                            <td class="text-center py-3 text-base">
                                {{ result.step.length}}
                            </td>
                            <td class="text-center py-3 text-base">
                                {{ moment(result.create).calendar() }}
                            </td>
                            <td class="text-center py-3 text-green-600 " v-if="result.delete == 0">
                                
                                publish
                            </td>
                            <td class="text-center py-3 text-redTextColor" v-else>
                                unpublish
                            </td>
                            <td
                                class="text-center customtextcolor7 underline text-base"
                            >
                                <Link
                                    :href="route('guideTool.edit', result.guideId)"
                                    :id="result.guideId"
                                    >Edit</Link
                                >
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="flex justify-center items-center my-10">
                    <Pagination class="z-10" :links="guides.links">
                    </Pagination>
                </div>
            </div>

            <div
                class="flex flex-col mt-10 md:flex-row w-full px-5 items-center justify-center text-white"
            >
                <!-- <Pagination/> -->
                <Link :href="route('guideTool.create')">
                    <div
                        class="absolute text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-xl text-sm px-5 py-2.5 mr-2 mb-2 bottom-5 right-3 focus:outline-none"
                    >
                        <a
                            href="#"
                            class="flex flex-row justify-center items-center space-x-3"
                        >
                            <img
                                src="../../../public/img/addlogo.png"
                                alt=""
                                class="w-5 h-5 pt-0.5"
                            />

                            <button type="button">
                                <span class="mx-1 font-bold text-base"
                                    >Add</span
                                >
                            </button>
                        </a>
                    </div>
                </Link>
            </div>
        </div>
    </div>
</template>
