<script setup>
import NavBar from "../Components/NavBar.vue";
import Header from "../Components/Header.vue";
import { Inertia } from "@inertiajs/inertia";
import { Link, Head } from "@inertiajs/inertia-vue3";
import { useForm } from "@inertiajs/inertia-vue3";
import { ref, inject, provide } from "vue";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/css/index.css";

const form = useForm({
    name: "",
    email: "",
});

const disable = ref(false);
const show = ref(true);
const show2 = ref(false);

const props = defineProps({
    errors: Object,
    charLength: Object,
});

let nameLength = ref(
    props.charLength.filter((item) => item.COLUMN_NAME == "name")[0]
        .CHARACTER_MAXIMUM_LENGTH
);
let mailLength = ref(
    props.charLength.filter((item) => item.COLUMN_NAME == "email")[0]
        .CHARACTER_MAXIMUM_LENGTH
);

//loading Bars
let isloading = ref(false);
let loader = ref("bars");
let color = ref("#000");
let width = ref("100");
let height = ref("200");
let fullPage = ref(true);

const submit = () => {
    Inertia.post(route("student.store"), form, {
        onError: (data) => {
            isloading.value = false;
        },
        onStart: (data) => {
            isloading.value = true;
        },
        onSuccess: () => {
            setTimeout(() => {
                Inertia.get(route("student.index"));
            }, 2000);

            // isloading.value = false;
        },
    });
};

window.history.forward();

let showMenu = ref(true);
provide("showMenu", showMenu);
</script>

<template>
    <!-------------------- Navbar&header -------------------->
    <NavBar active="3"> </NavBar>
    <Head title="Add Student"></Head>
    <Header
        headername="Add Student"
        class="w-full pr-36 transition-all duration-500"
        :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"
    />
    <!---------------- body ----------------------->
    <loading
        v-model:active="isloading"
        :is-full-page="fullPage"
        :loader="loader"
        :color="color"
        :width="width"
        :height="height"
    />
    <div
        class="absolute h-auto py-5 w-full headercustomleft top-32 bg-primaryBackground flex justify-center items-center transition-all duration-500 pr-36"
        :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"
    >
        <form
            @submit.prevent="submit"
            class="lg:w-5/6 md:w-4/6 xl:w-3/6 w-full h-auto bg-elementBackground rounded-2xl space-y-9 p-14"
            v-show="show"
        >
            <div
                v-if="$page.props.flash.worng"
                class="bg-red-600 text-white w-full items-center flex justify-center h-10 text-lg rounded-md mb-5"
            >
                <span
                    ><img
                        src="../../../public/img/ri_mail-close-line.png"
                        alt=""
                        class="w-7 h-7 pt-0.5"
                /></span>
                <p class="mx-5">{{ $page.props.flash.worng }}</p>
            </div>
            <div
                v-if="$page.props.flash.mailSent"
                class="bg-green-600 text-white w-full items-center flex justify-center h-10 text-lg rounded-md mb-5"
            >
                <span
                    ><img
                        src="../../../public/img/ri_mail-check-line.png"
                        alt=""
                        class="w-7 h-7 pt-0.5"
                /></span>
                <p class="mx-5">{{ $page.props.flash.mailSent }}</p>
            </div>
            <div class="flex flex-col">
                <label
                    for="first_name"
                    class="block mb-2 text-lg font-medium text-white md:text-xl"
                    >Name</label
                >

                <input
                    v-model="form.name"
                    type="text"
                    :maxlength="nameLength"
                    id="first_name"
                    class="w-full border border-white text-sm rounded-lg focus:ring-white focus:border-white focus:ring-2 block p-2.5 bg-elementBackground text-white"
                    placeholder="John"
                />
                <div
                    class="flex justify-end text-xs text-whiteTextColor"
                    v-text="nameLength - form.name.length + ' words left'"
                ></div>
                <div v-if="errors.name" class="text-red-700 text-md">
                    {{ errors.name }}
                </div>
            </div>
            <div class="">
                <label
                    for="email"
                    class="block mb-2 text-lg md:text-xl font-medium text-gray-900 dark:text-white"
                    >Email address</label
                >
                <input
                    v-model="form.email"
                    :maxlength="mailLength"
                    type="text"
                    id="email"
                    class="w-full border border-white text-sm rounded-lg focus:ring-white focus:border-white focus:ring-2 block p-2.5 bg-elementBackground text-white"
                    placeholder="john.doe@gmail.com"
                />
                <div
                    class="flex justify-end text-xs text-whiteTextColor"
                    v-text="mailLength - form.email.length + ' words left'"
                ></div>
                <div v-if="errors.email" class="text-red-700 text-md">
                    {{ errors.email }}
                </div>
            </div>

            <div class="flex justify-center items-center">
                <button
                    type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-2 focus:ring-blue-300 font-medium rounded-lg text-lg px-16 py-2"
                    :disabled="disable"
                >
                    Create
                </button>
            </div>
            <button
                disabled
                type="button"
                v-show="show2"
                class="py-2.5 px-5 mr-2 text-sm font-medium rounded-lg border focus:z-10 focus:ring-4 focus:outline-none focus:ring-blue-700 focus:text-blue-700 bg-gray-800 text-gray-400 border-gray-600 hover:text-white hover:bg-gray-700 inline-flex items-center"
            >
                <svg
                    aria-hidden="true"
                    role="status"
                    class="inline mr-2 w-4 h-4 text-gray-200 animate-spin dark:text-gray-600"
                    viewBox="0 0 100 101"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                        fill="currentColor"
                    />
                    <path
                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                        fill="#1C64F2"
                    />
                </svg>
            </button>
            <div class="w-4/6 text-white justify-start mt-5 mb-10">
                <button>
                    <Link
                        :href="route('student.index')"
                        class="underline underline-offset-4 hidden md:block"
                        >back
                    </Link>
                </button>
            </div>
        </form>
    </div>
</template>
