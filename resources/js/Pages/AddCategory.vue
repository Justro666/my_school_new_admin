<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import NavBar from "../Components/NavBar.vue";
import Header from "../Components/Header.vue";
import Toolsbar from "../Components/Toolsbar.vue";
import NotiError from "../Components/NotiError.vue";
import { Inertia } from "@inertiajs/inertia";
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/css/index.css';
import { ref, provide } from "vue";

const props = defineProps({
    errors: {
        type: Object,
    },
    characterLength: {
        type: Object
    }
});

let isloading = ref(false);
let loader = ref('bars');
let color = ref('#000');
let width = ref('100')
let height = ref('200')
let fullPage = ref(true)

const form = useForm({
    category_name: '',
    category_description: ''
});

let showError = ref(false);

const submit = () => {
    Inertia.post(route("categoryTool.store"), form, {
        onError: (data) => {
            
        },
        onStart: (data) => {
            isloading.value = true;
        },
        onFinish: () => {
            isloading.value = false;

            showError.value = true;

            setTimeout(() => {
                showError.value = false;
            }, 2000);
        }
    });
};

let titleLength = ref(props.characterLength[0].CHARACTER_MAXIMUM_LENGTH);
let descriptionLength = ref(props.characterLength[1].CHARACTER_MAXIMUM_LENGTH);

window.history.forward();

const showMenu = ref(true);

provide('showMenu', showMenu);
</script>

<template>
    <Head title="Add New Category" />

    <NavBar> </NavBar>
    <Header headername="Tools" class="w-full pr-36 transition-all duration-500" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"/>
    <loading v-model:active="isloading" :is-full-page="fullPage" :loader="loader" :color="color" :width="width"
        :height="height" />

    <div class="absolute h-auto w-full overflow-x-hidden py-5 headercustomleft top-32 customblack transition-all duration-500 pr-36" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'">

        <div class="absolute z-10 transition-all duration-500" v-if="showError" :class="showMenu ? 'right-64' : 'right-36'">
            <NotiError />
        </div>

        <Toolsbar active="4" />
        <div class="w-full h-full py-8 bg-secondaryBackground rounded-b-xl flex flex-col items-center">
            <form @submit.prevent="submit" class="w-full px-52">
                <div class="w-full flex flex-col space-y-4">
                    <label for="" class="text-whiteTextColor text-md">Name
                    </label>
                    <input type="text" v-model="form.category_name"
                        class="w-full rounded-xl bg-secondaryBackground text-whiteTextColor border-whiteTextColor focus:outline-0 focus:ring-whiteTextColor focus:border-whiteTextColor" />
                    <div class="flex justify-end text-xs text-whiteTextColor"
                        v-text="(titleLength - form.category_name.length + ' words left')"></div>
                    <div v-if="errors.category_name" class="text-red-500 font-bold text-md">
                        {{ errors.category_name }}
                    </div>


                    <label for="" class="text-whiteTextColor text-md">Description
                    </label>
                    <textarea type="text" v-model="form.category_description"
                        class="h-36 rounded-xl bg-secondaryBackground text-whiteTextColor border-whiteTextColor focus:outline-0 resize-none focus:ring-whiteTextColor focus:border-whiteTextColor scrollYStyle"></textarea>
                    <div class="flex justify-end text-xs text-whiteTextColor"
                        v-text="(descriptionLength - form.category_description.length + ' words left')"></div>
                    <div v-if="errors.category_description" class="text-red-500 font-bold text-md">
                        {{ errors.category_description }}
                    </div>

                    <div class="flex justify-between py-8">
                        <Link href="/categoryTool"
                            class="py-2 px-5 text-whiteTextColor text-sm bg-redTextColor rounded-xl flex items-center">
                        <img src="../../../public/img/delete.png" alt="" class="w-5 h-5 pt-0.5" />
                        <span class="mx-2">Cancel</span>
                        </Link>
                        <button
                            class="py-2 px-5 text-whiteTextColor text-sm bg-blueTextColor rounded-xl flex items-center">
                            <img src="../../../public/img/save.png" alt="" class="w-5 h-5 pt-0.5" />
                            <span class="mx-2">Save</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
<!-- testing -->