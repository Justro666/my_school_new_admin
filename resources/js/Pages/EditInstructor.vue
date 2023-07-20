<script setup>
import NavBar from "../Components/NavBar.vue";
import Header from "../Components/Header.vue";
import Pagination from "../Components/Pagination.vue";
import { Inertia } from "@inertiajs/inertia";
import { ref, inject, provide } from "vue";
import { useLoading } from "vue-loading-overlay";
import { useForm, Head } from "@inertiajs/inertia-vue3";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/css/index.css";

const props = defineProps({
    insData: {
        type: Object,
    },
    errors: {
        type: Object,
    },
    charLength:Object
});
// 
let addressLength = props.charLength.filter(
    (item) => item.COLUMN_NAME == "i_address"
)[0].CHARACTER_MAXIMUM_LENGTH;
let contactLength = props.charLength.filter(
    (item) => item.COLUMN_NAME == "i_contact"
)[0].CHARACTER_MAXIMUM_LENGTH;

const form = useForm({
    id: props.insData[0].id,
    address: props.insData[0].i_address,
    contact: props.insData[0].i_contact,
});

//loading Bars
let isloading = ref(false);
let fullPage = ref(true);

const submit = () => {
    Inertia.put(route("instructor.update", form.id), form, {
        onError: (data) => {},
        onStart: (data) => {
            isloading.value = true;
        },
        onFinish: () => {
            isloading.value = false;
        },
    });
};
window.history.forward();
const showMenu = ref(true);

provide("showMenu", showMenu);
</script>
<template>
    <Head title="Edit Instructor" />
    <NavBar active="4"> </NavBar>
    <Header
        headername="Edit Instructor"
        class="w-full pr-36 transition-all duration-500"
        :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"
    />
    <div
        class="absolute h-5/6 w-full my-4 headercustomleft top-28 bg-primaryBackground flex justify-center items-center flex-col customblack transition-all duration-500 pr-36"
        :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"
    >
        <loading
            v-model:active="isloading"
            :is-full-page="fullPage"
            :loader="'bars'"
            :color="'#000'"
            :width="'200'"
            :height="'300'"
        />
        <form
            @submit.prevent="submit"
            class="lg:w-5/6 md:w-4/6 xl:w-3/6 w-full h-auto bg-elementBackground rounded-2xl space-y-5 p-14"
        >
            <!-- Name -->
            <div class="flex flex-col">
                <label
                    for="name"
                    class="block mb-2 text-lg font-medium text-white md:text-lg"
                    >Name</label
                >
                <input
                    v-model="props.insData[0].i_name"
                    type="text"
                    disabled
                    class="focus:ring-white focus:border-white bg-elementBackground text-sm rounded-xl text-white w-full pointer-events-none"
                />
            </div>
            <!-- Email -->
            <div class="flex flex-col">
                <label
                    for="email"
                    class="block mb-2 text-lg font-medium text-white md:text-lg"
                    >Email</label
                >
                <input
                    v-model="props.insData[0].email"
                    id="email"
                    type="text"
                    disabled
                    class="focus:ring-white focus:border-white bg-elementBackground text-sm rounded-xl text-white w-full"
                />
            </div>
            <!-- Contact -->
            <div class="flex flex-col">
                <label
                    for="contact"
                    class="block mb-2 text-lg font-medium text-white md:text-lg"
                    >Contact</label
                >
                <input
                    v-model="form.contact"
                    id="contact"
                    :maxlength="contactLength"
                    type="text"
                    class="focus:ring-white focus:border-white bg-elementBackground text-sm rounded-xl text-white w-full"
                />
                 <div
                    class="flex justify-end text-xs text-whiteTextColor"
                    v-text="contactLength - form.contact.length + ' words left'"
                ></div>
                <div v-if="errors.contact" class="text-red-500">
                    {{ errors.contact }}
                </div>
            </div>

            <!-- Address -->
            <div class="flex flex-col">
                <label
                    for="address"
                    class="block mb-2 text-lg font-medium text-white md:text-lg"
                    >Address</label
                >
                <input
                    v-model="form.address"
                    type="text"
                    :maxlength="addressLength"
                    id="address"
                    class="focus:ring-white focus:border-white bg-elementBackground text-sm rounded-xl text-white w-full"
                />
                <div
                    class="flex justify-end text-xs text-whiteTextColor"
                    v-text="addressLength - form.address.length + ' words left'"
                ></div>
                <div v-if="errors.address" class="text-red-500">
                    {{ errors.address }}
                </div>
            </div>

            <div class="flex float-right mt-10">
                <button
                    class="py-2 px-5 text-whiteTextColor text-md bg-blueTextColor rounded-xl flex items-center"
                >
                    <img
                        src="../../../public/img/save.png"
                        alt=""
                        class="w-5 h-5 pt-0.5"
                    />
                    <span class="mx-2">Save</span>
                </button>
            </div>
        </form>
        
         <div class="absolute bottom-0 left-0 transition-all duration-500" :class="showMenu ? 'left-4' : 'left-0'">
            <button>
                <a
                    href="/instructor"
                    class="underline underline-offset-4 hidden md:block text-white"
                    >back</a
                >
            </button>
        </div>
    
    </div>
</template>
