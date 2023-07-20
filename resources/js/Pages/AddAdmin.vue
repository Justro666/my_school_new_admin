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
    roles: {
        type: Object,
    },
    errors: {
        type: Object,
    },
    charlength: {
        type: Object,
    },
});

const form = useForm({
    name: "",
    email: "",
    password: null,

    role: props.roles ? props.roles[0].id : "",
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

// for (let index = 0; index < props.charlength.length; index++) {
//     // let nameLength = ref(props.charlength[index].COLUMN_NAME == 'name')
//     if(props.charlength[index].COLUMN_NAME == 'name'){
//         let nameLength = ref()
//     }

// }
// let nameLength = ref(props.charlength[23].CHARACTER_MAXIMUM_LENGTH);
// let emailLength = ref(props.charlength[24].CHARACTER_MAXIMUM_LENGTH);

//loading Bars
let isloading = ref(false);
let fullPage = ref(true);

const submit = (isLoading) => {
    Inertia.post(route("admin.store"), form, {
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
    <Head title="Add Admin" />
    <NavBar active="5"> </NavBar>
    <Header
        headername="Add Admin"
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
        class="absolute h-5/6 w-full headercustomleft top-28 bg-primaryBackground flex justify-center items-center flex-col customblack transition-all duration-500 pr-36"
        :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"
    >
        <form
            class="lg:w-5/6 md:w-4/6 xl:w-3/6 w-full h-auto bg-elementBackground rounded-2xl space-y-5 p-14"
            @submit.prevent="submit"
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
            <div class="flex flex-col">
                <label
                    for="name"
                    class="block mb-2 text-lg font-medium text-white md:text-lg"
                    >Name</label
                >
                <input
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
                    class="block mb-2 text-lg font-medium text-white md:text-lg"
                    >Email</label
                >
                <input
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
                    class="block mb-2 text-lg font-medium text-white md:text-lg"
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
            <div class="flex flex-col">
                <label
                    for="name"
                    class="block mb-2 text-lg font-medium text-white md:text-lg"
                    >Role</label
                >
                <select
                    v-model="form.role"
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
                    href="/admin"
                    class="underline underline-offset-4 hidden md:block text-white"
                    >back</a
                >
            </button>
        </div>
    </div>
</template>
<style></style>
