<script setup>
import NavBar from "../Components/NavBar.vue";
import Header from "../Components/Header.vue";
import { Inertia } from "@inertiajs/inertia";
import { Link, Head } from "@inertiajs/inertia-vue3";
import { useForm } from "@inertiajs/inertia-vue3";
import { ref, inject, provide } from "vue";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/css/index.css";

const disable = ref(false);
const show = ref(true);
const show2 = ref(false);

const props = defineProps({
    errors: Object,
    students: Object,
});
console.log(props.students);
const form = useForm({
    name: props.students[0].name,
});

//loading Bars
let isloading = ref(false);
let loader = ref("bars");
let color = ref("#000");
let width = ref("100");
let height = ref("200");
let fullPage = ref(true);

const submit = () => {
    Inertia.put(route("student.update", props.students[0].id), form, {
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

const resetroute = () => {
    Inertia.delete(route("student.destroy", props.students[0].id), {
        onError: (data) => {},
        onStart: (data) => {
            isloading.value = true;
        },
        onSuccess: () => {
            setTimeout(() => {
                Inertia.get(route("student.index"));
            }, 2000);
        },
    });
};

let pop = ref(0);

const popup = () => {
    pop.value = 1;
};
let delete_pop = ref(1);
const delete_popup = () => {
    pop.value = 0;
};

let showMenu = ref(true);
provide("showMenu", showMenu);

window.history.forward();
</script>

<template>
    <!-------------------- Navbar&header -------------------->
    <NavBar active="3"> </NavBar>
    <Head title="Update Student"></Head>
    <Header
        headername="Update Student"
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
                    :disabled="pop == 1"
                    v-model="form.name"
                    type="text"
                    :maxlength="32"
                    id="first_name"
                    class="w-full border border-white text-sm rounded-lg focus:ring-white focus:border-white focus:ring-2 block p-2.5 bg-elementBackground text-white"
                    placeholder="John"
                />
                <div
                    class="flex justify-end text-xs text-whiteTextColor"
                    v-text="32 - form.name.length + ' words left'"
                ></div>
                <div v-if="errors.name" class="text-red-700 text-md">
                    {{ errors.name }}
                </div>
            </div>

            <div class="flex flex-row justify-around">
                <div class="flex justify-center items-center">
                    <button
                        :disabled="pop == 1"
                        @click="popup"
                        type="button"
                        class="text-white bg-red-600 hover:bg-red-800 focus:ring-2 focus:ring-blue-300 font-medium rounded-lg text-lg px-8 py-2"
                    >
                        Reset Password
                    </button>
                </div>
                <div class="flex justify-center items-center">
                    <button
                        :disabled="pop == 1"
                        type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-2 focus:ring-blue-300 font-medium rounded-lg text-lg px-16 py-2"
                    >
                        Update
                    </button>
                </div>
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
    <!-- delete_popup -->
    <div
        id="staticModal"
        data-modal-backdrop="static"
        class="w-auto h-auto fixed top-1/3 left-2/4 overflow-x-hidden overflow-y-hidden transition-opacity duration-100"
        v-show="pop == 1 && delete_pop == 1"
        tabindex="-1"
    >
        <div class="relative w-full h-full max-w-md md:h-auto">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button
                    type="button"
                    class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white"
                    data-modal-hide="popup-modal"
                ></button>
                <div class="p-6 text-center">
                    <svg
                        aria-hidden="true"
                        class="mx-auto mb-4 text-gray-400 w-14 h-14 dark:text-gray-200"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        ></path>
                    </svg>
                    <h3
                        class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400"
                    >
                        Are you sure you want to reset password?
                    </h3>

                    <Link @click="resetroute">
                        <button
                            type="button"
                            class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2"
                        >
                            Yes, I'm sure
                        </button>
                    </Link>
                    <button
                        @click="delete_popup"
                        type="button"
                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600"
                    >
                        No, cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete popup -->
</template>
