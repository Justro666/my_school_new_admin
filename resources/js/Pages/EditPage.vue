<script setup>
import NavBar from "../Components/NavBar.vue";
import Header from "../Components/Header.vue";
import Pagination from "../Components/Pagination.vue";
import { Inertia } from "@inertiajs/inertia";
import { ref, provide } from "vue";
import { Link, useForm } from "@inertiajs/inertia-vue3";
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/css/index.css';

const props = defineProps({
    errors: {
        type: Object
    },
    page: {
        type: Object,
    },
    characterLength: {
        type: Object
    }
});

const form = useForm({
    id: props.page.id,
    page_name: props.page.name,
    page_route: props.page.route,
    page_icon: null,
    menu: props.page.menu,
    publish: props.page.publish,
    _method: "put"
});

let imageFile = ref(props.page.icon);
let input = null;

let isloading = ref(false);
let loader = ref('bars');
let color = ref('#000');
let width = ref('100')
let height = ref('200')
let fullPage = ref(true)

const submit = () => {
    Inertia.post(route("pageList.update", form.id), form, {
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

let showError = ref(false);

const deleteroute = () => {
    Inertia.delete(route('pageList.destroy', form.id), {
        onError: (data) => {
            
        },
        onStart: (data) => {
            isloading.value = true;
        },
        onFinish: () => {
            isloading.value = false;
        },
        onSuccess: (data) => {
            if(data){
                unique = true;
            };

            showError.value = true;

            setTimeout(() => {
                showError.value = false;
            }, 2000);
        }
    })
}

let pageIcon;

form.menu == 1 ? pageIcon = true : pageIcon = false;

function toAddMenu() {
    pageIcon = !pageIcon;
    form.menu = pageIcon;

    pageIcon ? document.getElementById('pageIcon').style.display = "block"
        : document.getElementById('pageIcon').style.display = "none";
}

const showImagePreview = (event) => {
    input = event.target;
    if (input.files && input.files[0]) {
        const file = event.target.files[0];
        imageFile.value = URL.createObjectURL(file);
    }
};

function setMain() {
    form.menu = 1;
}

function setSub() {
    form.menu = 2;
}

let pop = ref(0);

const popup = () => {
    pop.value = 1;
};

const delete_popup = () => {
    pop.value = 0;
}

let titleLength = ref(props.characterLength[0].CHARACTER_MAXIMUM_LENGTH);
let routeLength = ref(props.characterLength[1].CHARACTER_MAXIMUM_LENGTH);

window.history.forward();

let showMenu = ref(true);
provide('showMenu', showMenu);
</script>

<template>

    <Head title="Edit Page" />

    <NavBar active=6> </NavBar>
    <Header headername="Edit Page" class="w-full pr-36 transition-all duration-500" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"/>
    <loading v-model:active="isloading" :is-full-page="fullPage" :loader="loader" :color="color" :width="width"
        :height="height" />

    <div class="absolute h-auto w-full overflow-x-hidden py-5 headercustomleft top-32 customblack bg-white transition-all duration-500 pr-36" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'">

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
                    <input type="checkbox" name="" id="" @click="toAddMenu" :checked="form.menu != 0" :disabled="pop == 1">
                </div>
                <div class="mx-auto mt-3">
                    <div class="flex flex-col space-y-6">
                        <div class="flex items-center">
                            <label for="" class="text-whiteTextColor mr-5">Page Name</label>
                            <input type="text"
                                class="w-96 rounded-xl bg-secondaryBackground text-whiteTextColor border-whiteTextColor focus:outline-0"
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
                                class="w-96 rounded-xl bg-secondaryBackground text-whiteTextColor border-whiteTextColor focus:outline-0"
                                v-model="form.page_route" :maxlength="routeLength" />
                        </div>
                        <div class="flex justify-end text-xs text-whiteTextColor"
                            v-text="(routeLength - form.page_route.length + ' words left')"></div>
                        <div v-if="errors.page_route" class="text-red-500 font-bold text-md">
                            {{ errors.page_route }}
                        </div>
                        <div class="flex flex-col items-center justify-center w-full" id="pageIcon"
                            v-show="form.menu != 0">
                            <div class="flex justify-around my-5 w-full">
                                <div class="flex items-center">
                                    <label for="mainMenu" class="text-whiteTextColor mr-3">Main Menu</label>
                                    <input type="radio" value="check" name="check" id="mainMenu"
                                        :checked="form.menu == 1" @click="setMain" class="cursor-pointer" />
                                </div>
                                <div class="flex items-center">
                                    <label for="subMenu" class="text-whiteTextColor mr-3">Sub Menu</label>
                                    <input type="radio" value="check" name="check" id="subMenu"
                                        :checked="form.menu == 2" @click="setSub" class="cursor-pointer" />
                                </div>
                            </div>
                            <label for="dropzone-file"
                                class="flex flex-col items-center justify-center w-full h-48  border-2 cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                <div class="relative flex flex-col items-center justify-center py-14 overflow-hidden">
                                    <div class="flex absolute w-full">
                                        <img :src="imageFile" alt="" class="w-full items-center">
                                    </div>
                                    <svg aria-hidden="true" class="w-10 h-10 mb-3 text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                        </path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span
                                            class="font-semibold">Click
                                            to upload</span></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX.
                                        800x400px)
                                    </p>
                                </div>
                                <input id="dropzone-file" type="file" @input="form.page_icon = $event.target.files[0]"
                                    @change="showImagePreview($event)" accept="image/*" class="hidden" />
                            </label>
                        </div>
                        <div v-if="errors.page_icon" class="text-red-500 font-bold text-md">
                            {{ errors.page_icon }}
                        </div>
                    </div>
                </div>

                <div class="flex justify-around mt-10">
                    <button v-if="form.publish == 0" @click="popup" type="button" :disabled="pop == 1"
                        class="py-2 px-5 text-whiteTextColor text-sm bg-redTextColor rounded-xl flex items-center">
                        <img src="../../../public/img/delete.png" alt="" class="w-5 h-5 pt-0.5" />
                        <span class="mx-2">Unpublish</span>
                    </button>
                    <button v-else @click="popup" type="button" :disabled="pop == 1"
                        class="py-2 px-5 text-whiteTextColor text-sm bg-green-700 rounded-xl flex items-center">
                        <img src="../../../public/img/restore-line.png" alt="" class="w-5 h-5 pt-0.5" />
                        <span class="mx-2">Publish</span>
                    </button>
                    <button type="submit" :disabled="pop == 1"
                        class="py-2 px-5 text-whiteTextColor text-sm bg-blueTextColor rounded-xl flex items-center">
                        <img src="../../../public/img/save.png" alt="" class="w-5 h-5 pt-0.5" />
                        <span class="mx-2">Update</span>
                    </button>
                    <!-- :href="route('pageList.destroy', form.page_id)" method="delete" -->
                </div>
            </div>
        </form>

        <div class="fixed left-42 bottom-5">
            <button>
                <a href="/pageList" class="underline underline-offset-4 hidden md:block text-whiteTextColor">back</a>
            </button>
        </div>
    </div>

    <!-- delete_popup -->
    <div id="staticModal" data-modal-backdrop="static"
        class="w-auto h-auto fixed top-1/3 left-2/4 overflow-x-hidden overflow-y-hidden transition-opacity duration-100"
        v-show="pop == 1" tabindex="-1">
        <div class="relative w-full h-full max-w-md md:h-auto">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button"
                    class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white"
                    data-modal-hide="popup-modal"></button>
                <div class="p-6 text-center">
                    <svg aria-hidden="true" class="mx-auto mb-4 text-gray-400 w-14 h-14 dark:text-gray-200" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400" v-if="props.publish == 0">
                        Are you sure you want to unpublish this?
                    </h3>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400" v-else>
                        Are you sure you want to publish this?
                    </h3>
                    <Link @click="deleteroute">
                    <button v-if="form.publish == 0" type="button"
                        class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                        Yes, I'm sure
                    </button>
                    <button v-else type="button"
                        class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                        Yes, I'm sure
                    </button>
                    </Link>
                    <button @click="delete_popup" type="button"
                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                        No, cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete popup -->
</template>

<style>

</style>
