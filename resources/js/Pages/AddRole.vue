<script setup>
import NavBar from "../Components/NavBar.vue";
import Header from "../Components/Header.vue";
import Pagination from "../Components/Pagination.vue";
import { Inertia } from "@inertiajs/inertia";
import { ref, provide } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/css/index.css';

const props = defineProps({
    errors: {
        type: Object,
    },
    characterLength: {
        type: Object
    }
});

let isloading =ref(false);
let loader = ref('bars');
let color = ref('#000');
let width = ref('100')
let height = ref('200')
let fullPage = ref(true)

const form = useForm({
    role_name: '',
});

const submit = () => {
    Inertia.post(route("addRole.store"), form, {
        onError: (data) => {

        },
        onStart:(data)=>{
            isloading.value = true ;
        },
        onFinish:()=>{
            isloading.value = false ;
        }
    });
};

let roleLength = ref(props.characterLength[0].CHARACTER_MAXIMUM_LENGTH);

window.history.forward();

let showMenu = ref(true);
provide('showMenu', showMenu);
</script>

<template>
    <Head title="Add New Role" />

    <NavBar active=8> </NavBar>
    <Header headername="Admin Permission" class="w-full pr-36 transition-all duration-500" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"/>
    <loading v-model:active="isloading" :is-full-page="fullPage" :loader="loader" :color="color" :width="width" :height="height"/>

    <div class="absolute h-auto w-full overflow-x-hidden py-5 headercustomleft top-32 customblack bg-white transition-all duration-500 pr-36" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'">
        <form @submit.prevent="submit">
            <div
                class="w-4/5 h-auto p-10 mx-auto relative bg-secondaryBackground rounded-xl flex flex-col"
            >
                <div class="mx-auto mt-3">
                    <label for="" class="text-whiteTextColor mr-5"
                        >Role Name</label
                    >
                    <input
                        type="text"
                        class="w-96 rounded-xl bg-secondaryBackground text-whiteTextColor border-whiteTextColor focus:outline-0 focus:ring-whiteTextColor focus:border-whiteTextColor"
                        v-model="form.role_name"
                        :maxlength="roleLength"
                    />
                    <div class="mt-3 flex justify-end text-xs text-whiteTextColor"
                            v-text="(roleLength - form.role_name.length + ' words left')"></div>
                    <div
                        v-if="errors.role_name"
                        class="text-red-500 font-bold text-md mt-5"
                    >
                        {{ errors.role_name }}
                    </div>
                </div>

                <div class="flex justify-end mt-5">
                    <button
                        type="submit"
                        class="py-2 px-5 text-whiteTextColor text-sm bg-blueTextColor rounded-xl flex items-center"
                    >
                        <img
                            src="../../../public/img/plus.png"
                            alt=""
                            class="w-5 h-5 pt-0.5"
                        />
                        <span class="mx-2">Add Role</span>
                    </button>
                </div>
            </div>
        </form>

        <div class="fixed left-42 bottom-5">
            <button>
                <a
                    href="/adminPermission"
                    class="underline underline-offset-4 hidden md:block text-whiteTextColor"
                    >back</a
                >
            </button>
        </div>
    </div>
</template>

<style></style>
