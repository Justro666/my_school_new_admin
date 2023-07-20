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

let isloading =ref(false);
let loader = ref('bars');
let color = ref('#000');
let width = ref('100')
let height = ref('200')
let fullPage = ref(true)

const form = useForm({
    blog_title: '',
    blog_description: '',
    blog_file: null,
});

let showError = ref(false);

const submit = () => {
    Inertia.post(route("blogTool.store"), form, {
        onError: (data) => {
            
        },
        onStart:(data)=>{
            isloading.value = true ;
        },
        onFinish:()=>{
            isloading.value = false ;

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
    <Head title="Add New Blog" />

    <NavBar> </NavBar>
    <Head title="Add Blog"></Head>
    <Header headername="Tools" class="w-full pr-36 transition-all duration-500" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"/>
    <loading v-model:active="isloading" :is-full-page="fullPage" :loader="loader" :color="color" :width="width" :height="height"/>

    <div class="absolute h-auto w-full overflow-x-hidden py-5 headercustomleft top-32 customblack transition-all duration-500 pr-36" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'">

        <div class="absolute z-10 transition-all duration-500" v-if="showError" :class="showMenu ? 'right-64' : 'right-36'">
            <NotiError />
        </div>

        <Toolsbar active="3" />

        <form @submit.prevent="submit">
            <div
                class="w-full h-auto py-8 bg-secondaryBackground rounded-b-xl flex flex-col items-center px-32"
            >
                <div class="w-full px-20 flex flex-col space-y-4">
                    <label for="" class="text-whiteTextColor">Title</label>
                    <input
                        type="text"
                        class="w-full rounded-xl bg-secondaryBackground text-whiteTextColor border-whiteTextColor focus:outline-0 focus:ring-whiteTextColor focus:border-whiteTextColor"
                        v-model="form.blog_title"
                    />
                    <div class="flex justify-end text-xs text-whiteTextColor" v-text="(titleLength - form.blog_title.length + ' words left')"></div>
                    <div
                        v-if="errors.blog_title"
                        class="text-red-500 font-bold text-md"
                    >
                        {{ errors.blog_title }}
                    </div>

                    <label for="" class="text-whiteTextColor"
                        >Description</label
                    >
                    <textarea
                        class="h-32 resize-none rounded-xl bg-secondaryBackground text-whiteTextColor border-whiteTextColor focus:outline-0 focus:ring-whiteTextColor focus:border-whiteTextColor scrollYStyle"
                        v-model="form.blog_description"
                    ></textarea>
                    <div class="flex justify-end text-xs text-whiteTextColor" v-text="(descriptionLength - form.blog_description.length + ' words left')"></div>
                    <div
                        v-if="errors.blog_description"
                        class="text-red-500 font-bold text-md"
                    >
                        {{ errors.blog_description }}
                    </div>
                    <label for="" class="text-whiteTextColor">File</label>
                    <div class="flex items-center justify-center w-full">
                        <label
                            for="dropzone-file"
                            class="flex flex-col items-center justify-center w-full h-48 border-2 cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                        >
                            <div
                                class="relative flex flex-col items-center justify-center py-14 overflow-hidden"
                            >
                                <div class="flex absolute w-full">
                                    <img
                                        :src="imageFile"
                                        alt=""
                                        class="w-full items-center"
                                    />
                                </div>
                                <svg
                                    aria-hidden="true"
                                    class="w-10 h-10 mb-3 text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                                    ></path>
                                </svg>
                                <p
                                    class="mb-2 text-sm text-gray-500 dark:text-gray-400"
                                >
                                    <span class="font-semibold"
                                        >Click to upload</span
                                    >
                                </p>
                                <p
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    SVG, PNG, JPG or GIF (MAX. 800x400px)
                                </p>
                            </div>
                            <input
                                id="dropzone-file"
                                type="file"
                                @input="form.blog_file = $event.target.files[0]"
                                @change="showImagePreview($event)"
                                accept="image/*"
                                class="hidden"
                            />
                        </label>
                    </div>
                    <div
                        v-if="errors.blog_file"
                        class="text-red-500 font-bold text-md"
                    >
                        {{ errors.blog_file }}
                    </div>
                    <div class="flex justify-between py-8">
                        <!-- <Link href="/blogTool"
                            class="py-2 px-5 text-whiteTextColor text-sm bg-redTextColor rounded-xl flex items-center">
                        <img src="../../../public/img/delete.png" alt="" class="w-5 h-5 pt-0.5" />
                        <span class="mx-2">Cancel</span>
                        </Link> -->
                        <Link
                            href="/blogTool"
                            class="py-2 px-5 text-whiteTextColor text-sm bg-redTextColor rounded-xl flex items-center"
                        >
                            <img
                                src="../../../public/img/delete.png"
                                alt=""
                                class="w-5 h-5 pt-0.5"
                            />
                            <span class="mx-2">Cancel</span>
                        </Link>
                        <button
                            type="submit"
                            class="py-2 px-5 text-whiteTextColor text-sm bg-blueTextColor rounded-xl flex items-center"
                        >
                            <img
                                src="../../../public/img/save.png"
                                alt=""
                                class="w-5 h-5 pt-0.5"
                            />
                            <span class="mx-1 font-bold text-base">Save</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    name: "ImageUploader",
    data() {
        return {
            imageFile: null,
            input: null,
            isImageUploading: false,
        };
    },
    methods: {
        showImagePreview(event) {
            this.input = event.target;
            if (this.input.files && this.input.files[0]) {
                let reader = new FileReader();
                reader.onload = (e) => {
                    this.imageFile = e.target.result;
                };
                reader.readAsDataURL(this.input.files[0]);
            }
        },
    },
};
</script>
