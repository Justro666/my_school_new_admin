<script setup>
import NavBar from "../Components/NavBar.vue";
import Header from "../Components/Header.vue";
import Pagination from "../Components/Pagination.vue";
import { Inertia } from "@inertiajs/inertia";
import { ref, provide } from "vue";
import { useForm, Head } from "@inertiajs/inertia-vue3";
import { Link } from "@inertiajs/inertia-vue3";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/css/index.css";

const props = defineProps({
    adminInfo: {
        type: Object,
    },
    roles: {
        type: Object,
    },
    errors: {
        type: Object,
    },
    sessionId: {
        Object,
    },
    charlength: {
        type: Object,
    },
});
let nameLength = props.charlength.filter(
    (item) => item.COLUMN_NAME == "name"
)[0].CHARACTER_MAXIMUM_LENGTH;
let emailLength = props.charlength.filter(
    (item) => item.COLUMN_NAME == "email"
)[0].CHARACTER_MAXIMUM_LENGTH;
let passwordLength = props.charlength.filter(
    (item) => item.COLUMN_NAME == "password"
)[0].CHARACTER_MAXIMUM_LENGTH;
// let nameLength = ref(props.charlength[23].CHARACTER_MAXIMUM_LENGTH);
// let emailLength = ref(props.charlength[24].CHARACTER_MAXIMUM_LENGTH);

const form = useForm({
    id: props.adminInfo.id,
    name: props.adminInfo.name,
    email: props.adminInfo.email,
    password: null,
    roles: props.adminInfo.role_id,
    
});

let delshow = ref(props.sessionId);

//loading Bars
let isloading = ref(false);
let fullPage = ref(true);
const submit = () => {
    Inertia.put(route("admin.update", form.id), form, {
        onError: (data) => {},
        onStart: (data) => {
            isloading.value = true;
        },
        onFinish: () => {
            isloading.value = false;
        },
    });
};
const deleteroute = () => {
    Inertia.delete(route("admin.destroy", form.id), {
        onError: (data) => {
             isloading.value = false;
        },
        onStart: (data) => {
            isloading.value = true;
        },
        onFinish: () => {
            isloading.value = false;
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

window.history.forward();
const showMenu = ref(true);

provide("showMenu", showMenu);
</script>

<template>
    <Head title="Edit Admin" />
    <NavBar active="5"> </NavBar>
    <Header
        headername="Edit Admin"
        class="w-full pr-36 transition-all duration-500"
        :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"
    />
    <loading
        v-model:active="isloading"
        :is-full-page="fullPage"
        :loader="'bars'"
        :color="'#000'"
        :width="'200'"
        :height="'300'"
    />
    <div
        class="absolute h-5/6 w-full my-10 headercustomleft top-32 bg-primaryBackground flex justify-center items-center flex-col customblack transition-all duration-500 pr-36"
        :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"
    >
        <form
            class="lg:w-5/6 md:w-4/6 xl:w-3/6 w-auto h-auto bg-elementBackground rounded-2xl space-y-5 p-14"
            @submit.prevent="submit"
        >
        <!-- Class Error -->
        <div
            v-if="$page.props.flash.errorclass"
                class="bg-red-600 text-white w-full items-center flex justify-center h-10 text-lg rounded-md mb-5"
        >
        <span
                    ><img
                        src="../../../public/img/errorC.png"
                        alt=""
                        class="w-7 h-7"
                /></span>
            <p  class="mx-5"> {{$page.props.flash.errorclass}}</p>
        </div>
        <!-- Mail Error -->
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
            <div class="flex flex-col">
                <label
                    for="name"
                    class="block mb-2 text-lg font-medium text-white md:text-xl"
                    >Name</label
                >
                <input
                    :disabled="pop == 1"
                    v-model="form.name"
                    :maxlength="nameLength"
                    type="text"
                    class="focus:ring-white focus:border-white bg-elementBackground text-sm rounded-xl text-white w-full"
                />
                <div
                    class="flex justify-end text-xs text-whiteTextColor"
                    v-text="nameLength - form.name.length + ' words left'"
                ></div>
                <div v-if="errors.name" class="text-red-500">
                    {{ errors.name }}
                </div>
            </div>
            <div class="flex flex-col">
                <label
                    for="name"
                    class="block mb-2 text-lg font-medium text-white md:text-xl"
                    >Email</label
                >
                <input
                    :disabled="pop == 1"
                    v-model="form.email"
                    :maxlength="emailLength"
                    type="text"
                    class="focus:ring-white focus:border-white bg-elementBackground text-sm rounded-xl text-white w-full"
                />
                <div
                    class="flex justify-end text-xs text-whiteTextColor"
                    v-text="emailLength - form.email.length + ' words left'"
                ></div>
                <div v-if="errors.email" class="text-red-500">
                    {{ errors.email }}
                </div>
            </div>
            <div class="flex flex-col">
                <label
                    for="name"
                    class="block mb-2 text-lg font-medium text-white md:text-xl"
                    >Password</label
                >
                <input
                    v-model="form.password"
                    type="password"
                    class="focus:ring-white focus:border-white bg-elementBackground text-sm rounded-xl text-white w-full"
                />
                <div v-if="errors.password" class="text-red-500">
                    {{ errors.password }}
                </div>
            </div>
            <div
                class="flex flex-col"
                v-show="props.sessionId != props.adminInfo.role_id"
            >
                <label
                    for="name"
                    class="block mb-2 text-lg font-medium text-white md:text-xl"
                    >Role</label
                >
                <select
                    v-model="form.roles"
                    :disabled="pop == 1"
                    name=""
                    id=""
                    class="focus:ring-white focus:border-white bg-elementBackground text-sm rounded-xl text-white w-full"
                >
                    <option
                        v-for="role in roles"
                        :key="role.id"
                        :value="role.id"
                    >
                        {{ role.r_name }}
                    </option>
                </select>
            </div>
            <!-- <div v-else></div> -->
            <div class="flex justify-between py-8">
                <!-- Delete -->
                <button
                    type="button"
                    class="py-2 px-5 text-whiteTextColor text-md bg-redTextColor rounded-xl flex items-center"
                    :disabled="pop == 1"
                    v-if="props.adminInfo.del_flg == 0"
                    @click="popup"
                    v-show="props.sessionId != props.adminInfo.role_id"
                >
                    <img
                        src="../../../public/img/delete.png"
                        alt=""
                        class="w-5 h-5 pt-0.5"
                    />
                    <span class="mx-2">Delete</span>
                </button>
                <button
                    type="button"
                    class="py-2 px-5 text-whiteTextColor text-md bg-green-600 rounded-xl flex items-center"
                    v-else
                    :disabled="pop == 1"
                    @click="popup"
                >
                    <img
                        src="../../../public/img/restore-line.png"
                        alt=""
                        class="w-5 h-5 pt-0.5"
                    />
                    <span class="mx-2">Restore</span>
                </button>

                <div></div>
                <!-- restore -->

                <button
                    class="py-2 px-5 text-whiteTextColor text-md bg-blueTextColor rounded-xl flex items-center"
                    :disabled="pop == 1"
                    v-show="props.adminInfo.del_flg == 0"
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

        <div class="absolute bottom-0 left-0 transition-all duration-500 py-10" :class="showMenu ? 'left-4' : 'left-0'">
            <button>
                <a
                    href="/admin"
                    class="underline underline-offset-4 hidden md:block text-white"
                    >back</a
                >
            </button>
        </div>
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
                        v-if="props.adminInfo.del_flg == 0"
                    >
                        Are you sure you want to delete this?
                    </h3>
                    <h3
                        class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400"
                        v-else
                    >
                        Are you sure you want to Restore this this?
                    </h3>
                    <Link @click="deleteroute">
                        <button
                            v-if="props.adminInfo.del_flg == 0"
                            type="button"
                            class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2"
                        >
                            Yes, I'm sure
                        </button>
                        <button
                            v-else
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

<style></style>
