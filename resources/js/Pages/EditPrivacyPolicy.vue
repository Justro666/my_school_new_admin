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
    privacypolicysInfo: {
        type: Object,
    },
    categories: {
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
    id: props.privacypolicysInfo.id,
    privacypolicys_title: props.privacypolicysInfo.title,
    privacypolicys_description: props.privacypolicysInfo.description,
    category: props.privacypolicysInfo.category,
    publish: props.privacypolicysInfo.publish
});

let showError = ref(false);

const submit = () => {
    Inertia.put(
        route("privacypolicyTool.update", form.id),
        form,
        {
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
        }
    );
};

const deleteroute = () => {
    Inertia.delete(route('privacypolicyTool.destroy', form.id), {
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
        },
    })
}

let pop = ref(0);

const popup = () => {
    pop.value = 1;
};

const delete_popup = () => {
    pop.value = 0;
}

let titleLength = ref(props.characterLength[0].CHARACTER_MAXIMUM_LENGTH);
let descriptionLength = ref(props.characterLength[1].CHARACTER_MAXIMUM_LENGTH);

window.history.forward();

const showMenu = ref(true);

provide('showMenu', showMenu);
</script>

<template>

    <Head title="Edit Privacy Policy" />

    <NavBar></NavBar>
    <Header headername="Tools" class="w-full pr-36 transition-all duration-500"
        :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'" />
    <loading v-model:active="isloading" :is-full-page="fullPage" :loader="loader" :color="color" :width="width"
        :height="height" />

    <div class="absolute h-auto w-full overflow-x-hidden py-5 headercustomleft top-32 customblack transition-all duration-500 pr-36"
        :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'">

        <div class="absolute z-10 transition-all duration-500" v-if="showError"
            :class="showMenu ? 'right-64' : 'right-36'">
            <NotiError />
        </div>

        <Toolsbar active="2" />
        <div class="w-full h-full py-8 bg-secondaryBackground rounded-b-xl flex flex-col items-center">
            <form @submit.prevent="submit">
                <div class="w-96 flex flex-col space-y-4">
                    <label for="" class="text-whiteTextColor">Title</label>
                    <input type="text"
                        class="rounded-xl bg-secondaryBackground text-whiteTextColor border-whiteTextColor focus:outline-0 focus:ring-whiteTextColor focus:border-whiteTextColor"
                        v-model="form.privacypolicys_title" />
                    <div class="flex justify-end text-xs text-whiteTextColor"
                        v-text="(titleLength - form.privacypolicys_title.length + ' words left')"></div>
                    <div v-if="errors.privacypolicys_title" class="text-red-500 font-bold text-md">
                        {{ errors.privacypolicys_title }}
                    </div>

                    <label for="" class="text-whiteTextColor">Description</label>
                    <textarea
                        class="h-32 resize-none rounded-xl bg-secondaryBackground text-whiteTextColor border-whiteTextColor focus:outline-0 focus:ring-whiteTextColor focus:border-whiteTextColor scrollYStyle"
                        v-model="form.privacypolicys_description"></textarea>
                    <div class="flex justify-end text-xs text-whiteTextColor"
                        v-text="(descriptionLength - form.privacypolicys_description.length + ' words left')"></div>
                    <div v-if="errors.privacypolicys_description" class="text-red-500 font-bold text-md">
                        {{ errors.privacypolicys_description }}
                    </div>

                    <label for="" class="text-whiteTextColor">Categories</label>
                    <select name="" id="" v-model="form.category"
                        class="rounded-xl bg-secondaryBackground text-whiteTextColor border-whiteTextColor focus:outline-0 focus:ring-whiteTextColor focus:border-whiteTextColor">
                        <option :value="cat.categoryId" v-for="cat in categories" :key="cat.categoryId">
                            {{ cat.categoryName }}
                        </option>
                    </select>
                    <div v-if="errors.category" class="text-red-500 font-bold text-md">
                        {{ errors.category }}
                    </div>

                    <div class="flex justify-between py-8">
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
                            <span class="mx-2">Save</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="py-5">
            <button>
                <a href="/privacypolicyTool"
                    class="underline underline-offset-4 hidden md:block text-whiteTextColor">back</a>
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
