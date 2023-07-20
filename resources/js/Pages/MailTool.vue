<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";
import NavBar from "../Components/NavBar.vue";
import Header from "../Components/Header.vue";
import Toolsbar from "../Components/Toolsbar.vue";
import Pagination from "../Components/Pagination.vue";
import { DOMDirectiveTransforms } from "@vue/compiler-dom";
import moment from "moment";

import { ref, watch,provide } from "vue";

const props = defineProps({
    mails: {
        type: Object,
    },
});

let maillist = ref({ ...props.mails });

let info = true;
let message = true;
let alert = true;
let filtering = [1, 2, 3];

let infoFun = () =>
    info ? filtering.push(1) : filtering.splice(filtering.indexOf(1), 1);

let messageFun = () =>
    message ? filtering.push(2) : filtering.splice(filtering.indexOf(2), 1);

let alertFun = () =>
    alert ? filtering.push(3) : filtering.splice(filtering.indexOf(3), 1);

let  filter = () =>
    maillist.value.data = props.mails.data.filter((item) =>
        filtering.includes(item.category));

window.history.forward();

const showMenu = ref(true);

provide('showMenu', showMenu);

// console.log(props.mails[0].mailId);

</script>

<template>
    <Head title="Mail List" />

    <NavBar @mouseover="showMenu = true" @mouseout="showMenu = false"></NavBar>
    <Header headername="Tools" class="w-full pr-36 transition-all duration-500" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"/>

    <div class="absolute h-auto w-full overflow-x-hidden py-5 headercustomleft top-32 customblack transition-all duration-500 pr-36" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'">
        <Toolsbar active="1" />

        <div
            class="w-full h-auto p-8 relative bg-secondaryBackground rounded-b-xl flex flex-col items-center"
        >
            <!-- Table Section -->
            <div class="p-4 w-full rounded-xl">
                <div
                    class="space-x-3 m-5 text-center md:text-start mb-10 text-whiteTextColor"
                >
                    <input
                        type="checkbox"
                        name="info"
                        id="info"
                        @click="
                            info = !info;
                            infoFun();
                            filter();
                        "
                        checked
                        class="p-2 rounded-md text-blueTextColor"
                    />
                    <label
                        for="info"
                        class="text-sm md:text-base text-blueTextColor"
                        >Information</label
                    >

                    <input
                        type="checkbox"
                        name="message"
                        id="message"
                        @click="
                            message = !message;
                            messageFun();
                            filter();
                        "
                        checked
                        value="2"
                        class="p-2 rounded-md text-yellowTextColor"
                    />
                    <label
                        for="message"
                        class="text-sm md:text-base text-yellowTextColor"
                        >Direct Message</label
                    >

                    <input
                        type="checkbox"
                        name="alert"
                        id="alert"
                        @click="
                            alert = !alert;
                            alertFun();
                            filter();
                        "
                        checked
                        value="3"
                        class="p-2 rounded-md text-tertiaryBackground"
                    />
                    <label
                        for="alert"
                        class="text-sm md:text-base text-tertiaryBackground"
                        >Alert</label
                    >
                </div>

                <table class="text-white w-full rounded-lg">
                    <tr class="opacity-70 customfontsize">
                        <th class="text-center py-4">TITLE</th>
                        <th class="py-4 w-96">DESCRIPTION</th>
                        <th class="py-4">SEND TO</th>
                        <th class="py-4">DATE</th>
                        <th class="py-4">CATEGORY</th>
                    </tr>
                    <tbody class="text-sm mt-3">
                        <tr
                            class="border-b"
                            v-for="result in maillist.data"
                            :key="result"
                            :class="
                                result.category == 1
                                    ? 'info'
                                    : result.category == 2
                                    ? 'message'
                                    : result.category == 3
                                    ? 'alert'
                                    : ''
                            "
                        >
                            <!-- <td>{{ result.p_title }}</td> -->
                            <td class="text-left py-4">{{ result.title }}</td>
                            <td class="text-start py-4">
                                {{ result.description }}
                            </td>
                            <td
                                v-if="result.username == null"
                                class="text-center py-4 text-yellowTextColor"
                            >
                                {{ result.classname }}
                            </td>
                            <td
                                v-if="result.classname == null"
                                class="text-center py-4 text-yellowTextColor"
                            >
                                {{ result.username }}
                            </td>
                            <td class="text-center py-4">
                                {{ moment(result.date).calendar() }}
                            </td>
                            <td
                                v-if="result.category == 1"
                                class="text-center py-4 text-blueTextColor"
                            >
                                Info
                            </td>
                            <td
                                v-else-if="result.category == 2"
                                class="text-center py-4 text-yellowTextColor"
                            >
                                Direct Message
                            </td>
                            <td
                                v-else-if="result.category == 3"
                                class="text-center py-4 text-tertiaryBackground"
                            >
                                Alert
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="flex justify-center items-center my-10">
                    <Pagination class="z-10" :links="mails.links"> </Pagination>
                </div>
            </div>

            <div
                class="flex flex-col mt-10 md:flex-row w-full px-5 items-center justify-center text-white"
            >
                <div
                    class="absolute text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-xl text-sm px-5 py-2.5 mr-2 mb-2 bottom-5 right-3 focus:outline-none"
                >
                    <Link
                        :href="route('mailtool.create')"
                        class="flex flex-row justify-center items-center space-x-3"
                    >
                        <img
                            src="../../../public/img/send.png"
                            alt=""
                            class="w-5 h-5 pt-0.5"
                        />

                        <button type="button">
                            <span>Send</span>
                        </button>
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
