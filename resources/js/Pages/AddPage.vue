<script setup>
import NavBar from "../Components/NavBar.vue";
import Header from "../Components/Header.vue";
import Pagination from "../Components/Pagination.vue";
import NotiError from "../Components/NotiError.vue";
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

const form = useForm({
    page_name: '',
    page_route: '',
    menu: null,
    pageIcon: null
});

let unique = ref(false);
let showError = ref(false);

const submit = () => {
    Inertia.post(route("pageList.store"), form, {
        onError: (data) => {
            
        },
        onStart: (data) => {
            isloading.value = true;
        },
        onFinish: () => {
            isloading.value = false;
        },
        onSuccess: (data) => {
            if (data) {
                unique = true;
            }

            showError.value = true;

            setTimeout(() => {
                showError.value = false;
            }, 2000);
        }
    });
};

let pageIcon = false;

let isloading = ref(false);
let loader = ref('bars');
let color = ref('#3265EE');
let width = ref('100')
let height = ref('200')
let fullPage = ref(true)

function toAddMenu() {
    pageIcon = !pageIcon;
    form.menu = pageIcon;

    pageIcon ? document.getElementById('pageIcon').style.display = "block"
        : document.getElementById('pageIcon').style.display = "none";
}

function setMain() {
    form.main = true;
    form.sub = false;


}

function setSub() {
    form.main = false;
    form.sub = true;


}

let titleLength = ref(props.characterLength[0].CHARACTER_MAXIMUM_LENGTH);
let routeLength = ref(props.characterLength[1].CHARACTER_MAXIMUM_LENGTH);

window.history.forward();

let showMenu = ref(true);
provide('showMenu', showMenu);
</script>

<template>

    <Head title="Add New Page" />

    <NavBar active=6> </NavBar>
    <Header headername="Add Page" class="w-full pr-36 transition-all duration-500"
        :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'" />
    <loading v-model:active="isloading" :is-full-page="fullPage" :loader="loader" :color="color" :width="width"
        :height="height" />

    <div class="absolute h-auto w-full overflow-x-hidden py-5 headercustomleft top-32 customblack bg-white transition-all duration-500 pr-36"
        :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'">

        <div class="absolute z-10 transition-all duration-500" v-if="showError" :class="showMenu ? 'right-64' : 'right-36'">
            <NotiError />
        </div>

        <form @submit.prevent="submit">
            <div class="w-4/5 h-auto p-10 mx-auto relative bg-secondaryBackground rounded-xl flex flex-col">
                <div class="absolute top-5 left-5 text-red-500 font-bold text-md" v-show="unique">
                    Page name and page route must be unique!
                </div>
                <div class="absolute top-5 right-5 flex items-center justify-center space-x-3">
                    <p class="text-whiteTextColor">Menu</p>
                    <input type="checkbox" name="" id="" @click="toAddMenu">
                </div>
                <div class="mx-auto mt-3">
                    <div class="flex flex-col space-y-6">
                        <div class="flex items-center">
                            <label for="" class="text-whiteTextColor mr-5">Page Name</label>
                            <input type="text"
                                class="focus:ring-white focus:border-white border-white text-white rounded-xl block w-96 bg-elementBackground"
                                v-model="form.page_name" :maxlength="titleLength" />
                        </div>
                        <div class="flex justify-end text-xs text-whiteTextColor"
                            v-text="(titleLength - form.page_name.length + ' words left')"></div>
                        <div v-if="errors.page_name" class="text-red-500 font-bold text-md">
                            {{ errors.page_name }}
                        </div>

                        <div class="flex items-center">
                            <label for="" class="text-whiteTextColor mr-5">Page Route</label>
                            <input type="text"
                                class="focus:ring-white focus:border-white border-white text-white rounded-xl block w-96 bg-elementBackground"
                                v-model="form.page_route" :maxlength="routeLength" />
                        </div>
                        <div class="flex justify-end text-xs text-whiteTextColor"
                            v-text="(routeLength - form.page_route.length + ' words left')"></div>
                        <div v-if="errors.page_route" class="text-red-500 font-bold text-md">
                            {{ errors.page_route }}
                        </div>
                        <div class="flex items-center justify-center w-full" id="pageIcon" style="display: none">
                            <div class="flex justify-around my-5">
                                <div class="flex items-center">
                                    <label for="mainMenu" class="text-whiteTextColor mr-3">Main Menu</label>
                                    <input type="radio" value="check" name="check" id="mainMenu" @click="setMain"
                                        class="cursor-pointer" />
                                </div>
                                <div class="flex items-center">
                                    <label for="subMenu" class="text-whiteTextColor mr-3">Sub Menu</label>
                                    <input type="radio" value="check" name="check" id="subMenu" checked="true"
                                        @click="setSub" class="cursor-pointer" />
                                </div>
                            </div>
                            <label for="dropzone-file"
                                class="flex flex-col items-center justify-center w-full h-48 border-2 cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                <div class="relative flex flex-col items-center justify-center py-14 overflow-hidden">
                                    <div class="flex absolute w-full">
                                        <img :src="imageFile" alt="" class="w-full items-center" />
                                    </div>
                                    <svg aria-hidden="true" class="w-10 h-10 mb-3 text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                        </path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-semibold">Click to upload</span>
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        SVG, PNG, JPG or GIF (MAX. 800x400px)
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Page icon to show in menu bar.
                                    </p>
                                </div>
                                <input id="dropzone-file" type="file" @input="form.pageIcon = $event.target.files[0]"
                                    @change="showImagePreview($event)" accept="image/*" class="hidden" />
                            </label>
                        </div>
                        <div v-if="errors.pageIcon" class="text-red-500 font-bold text-md">
                            {{ errors.pageIcon }}
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-5">
                    <button type="submit"
                        class="py-2 px-5 text-whiteTextColor text-sm bg-blueTextColor rounded-xl flex items-center">
                        <img src="../../../public/img/plus.png" alt="" class="w-5 h-5 pt-0.5" />
                        <span class="mx-2">Add Page</span>
                    </button>
                </div>
            </div>
        </form>

        <div class="fixed left-42 bottom-5">
            <button>
                <a href="/pageList" class="underline underline-offset-4 hidden md:block text-whiteTextColor">back</a>
            </button>
        </div>
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
