<script setup>
import NavBar from "../Components/NavBar.vue";
import Header from "../Components/Header.vue";
import Pagination from "../Components/Pagination.vue";
import { Link, Head, usePage } from "@inertiajs/inertia-vue3";
import { provide, ref } from "vue";
import { Inertia } from "@inertiajs/inertia";

let props = defineProps({
    contacts: Object,
    unreadcontact: Object,
    unreplycontact: Object
})
// console.log(props.contacts.links);

let active = ref(1);

let showMenu = ref(true);
provide("showMenu", showMenu);
window.history.forward();
</script>

<template>
    <!-------------------- Navbar&header -------------------->

    <NavBar />
    <Head title="Messages"> </Head>
    <Header
        headername="Messages"
        class="w-full pr-36 transition-all duration-500"
        :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"
    />

    <!---------------- body ----------------------->

    <div
        class="absolute h-auto w-full headercustomleft top-32 customblack transition-all duration-500 pr-36"
        :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"
    >

    <div class=" w-full h-5/6 flex flex-col mt-10">
        <div class="flex flex-row text-white text-center h-full">
            <div :class="{'bg-elementBackground' : active == 1}" class="md:w-1/6 h-12 rounded-tl-xl md:text-xl cursor-pointer flex items-center justify-center" @click="active = 1">
                All <span class="bg-blue-500 text-white text-xs font-medium mr-2 ml-2 px-2.5 py-0.5 rounded"> {{contacts.data.length}} message</span>
            </div>
            <div :class="{'bg-elementBackground' : active == 2}" class="md:w-1/6 h-12 md:text-xl cursor-pointer flex items-center justify-center" @click="active = 2">
                Unread <span class="bg-green-600 text-white text-xs font-medium mr-2 ml-2 px-2.5 py-0.5 rounded" v-show="unreadcontact.data.length > 0">{{unreadcontact.data.length}} New</span>
            </div>
            <div :class="{'bg-elementBackground' : active == 3}" class="md:w-1/6 h-12 rounded-tr-xl md:text-xl cursor-pointer flex items-center justify-center" @click="active = 3">
                Unreply <span class="bg-yellow-600 text-white text-xs font-medium mr-2 ml-2 px-2.5 py-0.5 rounded-full" v-show="unreplycontact.data.length > 0">{{unreplycontact.data.length}} New</span>
            </div>
        </div>
        <div v-if="active == 1" class="px-10 mb-6 w-full bg-elementBackground mr-10 rounded-b-xl">
            <table class="text-white w-full bg-elementBackground rounded-lg mb-5">
                <tr class="opacity-70 customfontsize" v-show="contacts.data.length > 0">
                    <th class="text-start text-lg pt-4">Name</th>
                    <th class="text-start text-lg pt-4">Email</th>
                    <th class="text-start text-lg pt-4">Message</th>
                    <th class="text-start text-lg pt-4">Date</th>
                    <th class="text-start text-lg pt-4"></th>
                </tr>
                <tbody class="text-sm customfontsize" v-for="contact in contacts.data" :key="contact">
                    <tr class="cusborder border-b border-slate-600" v-show="contacts.data.length > 0">
                        <td class="py-3">{{contact.username}}</td>
                        <td class="">{{contact.email}}</td>
                        <td class="">{{contact.message.substring(0,25).concat('....') }}</td>
                        <td class="">{{contact.created_at.substring(0,10)}}</td>
                        <td class=" text-yellowTextColor underline" :class="{'text-greenTextColor': contact.reply}">
                                <Link :href="route('contact.show', contact.id)">

                                    <span v-if="contact.reply == null">Reply</span>
                                    <span class="" v-else>View</span>
                                </Link>

                        </td>
                    </tr>

                </tbody>
            </table>
            <div
                class="flex justify-center items-center my-10 pb-3"
                v-show="contacts.links.length > 0"
            >
                <Pagination class="z-10" :links="contacts.links">
                </Pagination>
            </div>
        </div>

        <div v-if="active == 2">
            <p class="text-white text-lg pt-10" v-show="unreadcontact.data.length == 0">There is no unread Message!</p>
            <div  class="px-10 mb-6 w-full bg-elementBackground mr-10 rounded-b-xl" v-show="unreadcontact.data.length > 0">

            <table class="text-white w-full bg-elementBackground rounded-lg mb-5" >
                <tr class="opacity-70 customfontsize" v-show="unreadcontact.data.length > 0">
                    <th class="text-start text-lg pt-4">Name</th>
                    <th class="text-start text-lg pt-4">Email</th>
                    <th class="text-start text-lg pt-4">Message</th>
                    <th class="text-start text-lg pt-4">Date</th>
                    <th class="text-start text-lg pt-4"></th>
                </tr>
                <tbody class="text-sm customfontsize" v-for="contact in unreadcontact.data" :key="contact">
                    <tr class="cusborder border-b border-slate-600" v-show="unreadcontact.data.length > 0">
                        <td class="py-3">{{contact.username}}</td>
                        <td class="">{{contact.email}}</td>
                        <td class="">{{contact.message.substring(0,25).concat('....') }}</td>
                        <td class="">{{contact.created_at.substring(0,10)}}</td>
                        <td class=" text-yellowTextColor underline">
                                <Link :href="route('contact.show', contact.id)">
                                    <span>Reply</span>
                                </Link>

                        </td>
                    </tr>

                </tbody>
            </table>
            <div
                class="flex justify-center items-center my-10 pb-3"
                v-show="unreadcontact.links.length > 0"
            >
                <Pagination class="z-10" :links="unreadcontact.links">
                </Pagination>
            </div>
        </div>
        </div>

        <div v-if="active == 3" >
            <p class="text-white text-lg pt-10" v-show="unreplycontact.data.length == 0">There is no unreply Message!</p>
            <div class="px-10 mb-6 w-full bg-elementBackground mr-10 rounded-b-xl">
            <table class="text-white w-full bg-elementBackground rounded-lg mb-5">
                <tr class="opacity-70 customfontsize" v-show="unreplycontact.data.length > 0">
                    <th class="text-start text-lg pt-4">Name</th>
                    <th class="text-start text-lg pt-4">Email</th>
                    <th class="text-start text-lg pt-4">Message</th>
                    <th class="text-start text-lg pt-4">Date</th>
                    <th class="text-start text-lg pt-4"></th>
                </tr>
                <tbody class="text-sm customfontsize" v-for="contact in unreplycontact.data" :key="contact">
                    <tr class="cusborder border-b border-slate-600" v-show="unreplycontact.data.length > 0">
                        <td class="py-3">{{contact.username}}</td>
                        <td class="">{{contact.email}}</td>
                        <td class="">{{contact.message.substring(0,25).concat('....') }}</td>
                        <td class="">{{contact.created_at.substring(0,10)}}</td>
                        <td class=" text-yellowTextColor underline">
                                <Link :href="route('contact.show', contact.id)">
                                    <span>Reply</span>
                                </Link>

                        </td>
                    </tr>

                </tbody>
            </table>
            <div
                class="flex justify-center items-center my-10 pb-3"
                v-show="unreplycontact.links.length > 0"
            >
                <Pagination class="z-10" :links="unreplycontact.links">
                </Pagination>
            </div>
        </div>
        </div>

    </div>

    </div>
</template>


<style>

</style>
