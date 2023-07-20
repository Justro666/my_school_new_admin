<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";
import NavBar from "../Components/NavBar.vue";
import Header from "../Components/Header.vue";
import Toolsbar from "../Components/Toolsbar.vue";
import Pagination from "../Components/Pagination.vue";
import { DOMDirectiveTransforms } from "@vue/compiler-dom";
import moment from "moment";
import { ref, provide } from "vue";

const props = defineProps({
    permissionPages: {
        type: Object,
    },
});

console.log(props.permissionPages);

window.history.forward();

let showMenu = ref(true);
provide('showMenu', showMenu);
</script>

<template>

    <Head title="Page List" />

    <NavBar active=6> </NavBar>
    <Header headername="Page List" class="w-full pr-36 transition-all duration-500"
        :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'" />

    <div class="absolute h-auto w-full overflow-x-hidden py-5 headercustomleft top-32 customblack transition-all duration-500 pr-36"
        :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'">
        <div class="w-full h-auto p-8 relative bg-secondaryBackground rounded-xl flex flex-col items-center">
            <!-- Table Section -->
            <div class="p-4 w-full rounded-xl">
                <table class="text-white w-full rounded-lg">
                    <tr class="opacity-70 customfontsize">
                        <th class="text-center py-4">PAGE NAME</th>
                        <th class="py-4">PAGE ROUTE</th>
                        <th class="py-4">PERMISSION</th>
                        <th class="py-4">PUBLISH</th>
                        <th class="py-4">MENU</th>
                        <th class="py-4">ICON</th>
                        <th class="py-4">SETTING</th>
                    </tr>
                    <tbody class="text-sm mt-3">
                        <tr class="border-b" v-for="result in props.permissionPages.data" :key="result">
                            <td class="text-left py-4">{{ result.pageName }}</td>
                            <td class="text-center py-4">
                                {{ result.pageRoute }}
                            </td>
                            <td class="text-center py-4 customtextcolor7 font-bold text-md">
                                {{
                                    result.role.length > 0 ? result.role.map(item => item.r_name).join(" / ") : "Not Set"
                                }}
                            </td>
                            <td class="text-center py-3 font-bold">
                                <span v-if="result.publish == 0" class="text-green-600">Publish</span>
                                <span v-else class="text-red-600">Unpublish</span>
                            </td>
                            <td class="text-center py-3 text-whiteTextColor">
                                <span v-if="result.menu == 1">Main</span>
                                <span v-if="result.menu == 2">Sub</span>
                                <span v-if="result.menu == 0">None</span>
                            </td>
                            <td class="text-center py-3 font-bold text-green-500">
                                <span v-if="result.pageIcon == null" class="text-red-600">No Icon</span>
                                <span v-else class="w-full">
                                    <img :src="result.pageIcon" class="w-8 mx-auto" alt="" />
                                </span>
                            </td>
                            <td class="text-center py-4 customtextcolor7 underline">
                                <Link :href="route('pageList.edit', result.pageId)">
                                    Edit
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="flex justify-center items-center my-10">
                    <Pagination class="z-10" :links="permissionPages.links">
                    </Pagination>
                </div>
            </div>

            <div class="flex flex-col mt-10 md:flex-row w-full px-5 items-center justify-center text-white">
                <div
                    class="absolute text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-xl text-sm px-5 py-2.5 mr-2 mb-2 bottom-5 right-3 focus:outline-none">
                    <Link :href="route('pageList.create')" class="flex flex-row justify-center items-center space-x-3">
                    <img src="../../../public/img/addlogo.png" alt="" class="w-5 h-5 pt-0.5" />

                    <button type="button">
                        <span>Add Page</span>
                    </button>
                    </Link>
                </div>
            </div>
        </div>

        <div class="fixed left-42 bottom-5">
            <button>
                <a href="/adminPermission"
                    class="underline underline-offset-4 hidden md:block text-whiteTextColor">back</a>
            </button>
        </div>
    </div>
</template>
