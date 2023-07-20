<script setup>
import NavBar from "../Components/NavBar.vue";
import Header from "../Components/Header.vue";
import Pagination from "../Components/Pagination.vue";
import { Inertia } from "@inertiajs/inertia";
import { createVNode, ref , provide} from "vue";
import { useForm, Head } from "@inertiajs/inertia-vue3";
import { Link } from "@inertiajs/inertia-vue3";
import Toolsbar from "../Components/Toolsbar.vue";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/css/index.css";

const props = defineProps({
    guideInfo: {
        type: Object,
    },
    errors: {
        type: Object,
    },
    charLength:Object,
    stepLength:Object
});

let titleLength = props.charLength.filter((item)=>item.COLUMN_NAME == 'g_title')[0].CHARACTER_MAXIMUM_LENGTH
let steptLength = props.stepLength.filter((item)=>item.COLUMN_NAME == 'step_title')[0].CHARACTER_MAXIMUM_LENGTH
let descLength = props.stepLength.filter((item)=>item.COLUMN_NAME == "step_description")[0].CHARACTER_MAXIMUM_LENGTH
// let titleLength = ref(props.charLength[0].CHARACTER_MAXIMUM_LENGTH);
// let steptLength = ref(props.stepLength[0].CHARACTER_MAXIMUM_LENGTH);
// let descLength = ref(props.stepLength[1].CHARACTER_MAXIMUM_LENGTH);
let imageFile = ref(props.guideInfo.guide_step.map((item) => item.step_photo));
let input = null;

const showImagePreview = (event) => {
    input = event.target;
    if (input.files && input.files[0]) {
        const file = event.target.files[0];
        imageFile.value[event.target.id] = URL.createObjectURL(file);
        console.log(imageFile);
    }
};

const inputs = ref(props.guideInfo.guide_step.length);
const addInput = () => {
    inputs.value += 1;
};

const removeInput = (index) => {
    form.steptitle.splice(index, 1);
    form.description.splice(index, 1);
    form.stepid.splice(index,1);
    imageFile.value.splice(index,1);
    inputs.value -= 1;
};
let pop = ref(0);
const popup = () => {
    pop.value = 1;

};
let delete_pop = ref(1);
const delete_popup = () => {
    pop.value = 0;

};

const form = useForm({
    id: props.guideInfo.id,
    guidetitle: props.guideInfo.g_title,
    stepid: props.guideInfo.guide_step.map((item) => item.id),
    steptitle: props.guideInfo.guide_step.map((item) => item.step_title),
    description: props.guideInfo.guide_step.map(
        (item) => item.step_description
    ),
    step_file:props.guideInfo.guide_step.map((item)=> item.step_photo),
    fake_id:[],
    input: inputs,
    del_flg: props.guideInfo.del_flg,
    _method: "put",
});



//loading Bars
let isloading = ref(false);
let fullPage = ref(true);

const submit = () => {
    form.fake_id = [];
    for (let i = 0; i < form.steptitle.length; i++) {
        if (form.stepid[i]) {
            
            form.fake_id.push(form.stepid[i]);
        } else {
            form.fake_id.push(null);
        }
        
    }

    console.log(form.fake_stepfile);
    Inertia.post(route("guideTool.update", form.id), form, {
        onError: (data) => {

        },

        onStart: (data) => {
            isloading.value = true;
        },
        onFinish: () => {
            isloading.value = false;
        },
    });
};
const deleteroute = () => {
    Inertia.delete(route("guideTool.destroy", form.id), {
        onError: (data) => {

        },
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

provide('showMenu', showMenu);
</script>

<template>
    <Head title="Edit Guide" />
    <NavBar active="8"> </NavBar>
    <Header headername="Edit Guide" class="w-full pr-36 transition-all duration-500" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"/>

    <div
        class="absolute h-full w-full p-5 headercustomleft top-32 customblack transition-all duration-500 pr-36" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"
       
    >
        <Toolsbar active="5" />
       <loading v-model:active="isloading" :is-full-page="fullPage" :loader="'bars'" :color="'#000'" :width="'200'" :height="'300'"/>
        <div
            class="w-full h-auto p-8 relative bg-secondaryBackground rounded-b-xl flex flex-col items-center"
        >
            <form @submit.prevent="submit" class="w-full pl-10">
                <!-- guide title -->
                <div class="w-full px-44">
                    <label for="Guidetitle" class="text-white text-md block">
                        Guide Title</label
                    >
                    <input
                        type="text"
                        id="Guidetitle"
                        v-model="form.guidetitle"
                        :maxlength="titleLength"
                        :disabled="pop == 1"
                        class="focus:ring-white focus:border-white border-white text-white text-sm rounded-xl block w-11/12 bg-elementBackground p-2"
                    />
                    <!-- <div class="flex float-right pr-28 text-xs text-whiteTextColor"
                                v-text="(titleLength - form.guidetitle.length + ' words left')"></div> -->
                    <div v-if="errors.guidetitle" class="text-red-500">
                        {{ errors.guidetitle }}
                    </div>
                </div>

                <!-- Step  -->
                <div v-for="input in inputs" :key="input" class=" w-full">
                    <div id="step">
                        <div class="flex flex-row justify-between  px-44">
                            <p class="text-xl text-white pt-6 font-bold">
                                Step {{ input }}
                            </p>
                            <div
                                class="pt-6 pr-20"
                                @click="removeInput(input - 1)"
                                v-show="input > 1"
                                :disabled="pop == 1"
                            >
                                <button type="button">
                                    <img
                                        src="../../../public/img/minus-circle.svg"
                                        alt=""
                                        class="w-8 h-8"
                                    />
                                </button>
                            </div>
                        </div>

                        <div class="pt-8 w-full  px-44">
                            <label
                                for="steptitle"
                                class="text-white text-md block"
                            >
                                Step Title</label
                            >
                            <input
                                v-model="form.steptitle[input - 1]"
                                :disabled="pop == 1"
                                type="text"
                                :maxlength="steptLength"
                                id="steptitle"
                                class="focus:ring-white focus:border-white border-white text-white text-sm rounded-xl block w-11/12 bg-elementBackground p-2"
                                required
                            />
                             <div class="flex float-right pr-28 text-xs text-whiteTextColor" v-if="form.steptitle.length > input-1"
                                v-text="((steptLength - form.steptitle[input - 1].length ) + ' words left')"></div>
                        </div>
                        <!-- textarea  -->
                        <div class="pt-5 w-full px-44">
                            <label
                                for="Description"
                                class="text-white text-md block"
                            >
                                Description</label
                            ><textarea
                                name=""
                                v-model="form.description[input - 1]"
                                :disabled="pop == 1"
                                id="Description"
                                :maxlength="descLength"
                                required
                                class="h-48 w-11/12 resize-none rounded-xl bg-secondaryBackground text-whiteTextColor focus:outline-0 focus:ring-white focus:border-white border-white"
                            >
                            </textarea>
                            <div class="flex float-right pr-28 text-xs text-whiteTextColor" v-if="form.description.length > input-1"
                                v-text="((descLength - form.description[input - 1].length ) + ' words left')"></div>
                        </div>
                        <div class="mt-5 px-44">
                            <label for="" class="text-white text-md"
                                >File</label
                            >
                        </div>
                            <!-- image -->
                        <div
                            class="flex items-center justify-center w-11/12 mt-5 px-44 ml-3 flex-col"
                        >
                            <label
                                :for="input - 1"
                                class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                            >
                                <div
                                    class="relative flex flex-col items-center justify-center pt-5 pb-6 overflow-hidden"
                                >
                                    <div class="flex absolute w-full h-full">
                                        <img
                                            :src="imageFile[input - 1]"
                                            alt=""
                                            class="w-full h-full items-center"
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
                                    :disabled="pop == 1"
                                    :id="input - 1"
                                    :fileid="input - 1"
                                    type="file"
                                    @input="
                                        form.step_file[input - 1] =
                                            $event.target.files
                                    "
                                    @change="showImagePreview($event)"
                                    accept="image/*"
                                    class="hidden"
                                />
                            </label>
                            <div v-if="errors.step_file" class="text-red-500">
                                {{ errors.step_file }}
                            </div>
                        </div>

                        <!-- line -->
                        <hr
                            class="mt-14 h-px w-11/12 bg-gray-200 border-0 ml-16"
                            id="asdas"
                        />
                    </div>
                </div>

                <div
                    class="text-white bg-blue-700 w-1/6 rounded-xl text-sm px-5 py-2.5 mt-9 flex flex-row justify-center items-center space-x-3"
                >
                    <img
                        src="../../../public/img/addlogo.png"
                        alt=""
                        class="w-5 h-5 pt-0.5"
                    />
                    <button
                        type="button"
                        @click="addInput"
                        :disabled="pop == 1"
                        :class="{
                            block: inputs < 10,
                            hidden: inputs == 10,
                        }"
                    >
                        Create New Step
                    </button>
                </div>

                <div class="flex justify-between py-8">
                    <button
                        @click="popup"
                        :disabled="pop == 1"
                        v-if="form.del_flg == 1"
                        type="button"
                        class="py-2 px-5 text-whiteTextColor text-md bg-redTextColor rounded-xl flex items-center"
                    >
                        <img
                            src="../../../public/img/unpublish-content.png"
                            alt=""
                            class="w-5 h-5 pt-0.5"
                        />
                        <span class="mx-2">Unpublish</span>
                    </button>

                    <button
                        :disabled="pop == 1"
                        @click="popup"
                        type="button"
                        v-else
                        class="py-2 px-5 text-whiteTextColor text-md bg-green-600 rounded-xl flex items-center"
                    >
                        <img
                            src="../../../public/img/publish-content.png"
                            alt=""
                            class="w-5 h-5 pt-0.5"
                        />
                        <span class="mx-2">Publish</span>
                    </button>

                    <button
                        class="py-2 px-5 text-whiteTextColor text-md bg-blueTextColor rounded-xl flex items-center"
                        :disabled="pop == 1"
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
            <!-- <button
                
                class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                type="button"
                @click="popup"
            >
                Toggle modal
            </button> -->
        </div>
        <div class="py-10">
            <button>
                <a
                    href="/guideTool"
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
        class="w-auto h-auto fixed top-1/4 left-2/4 overflow-x-hidden overflow-y-hidden"
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
                        v-if="form.del_flg == 1"
                    >
                        Are you sure you want to unpublish this?
                    </h3>
                    <h3
                        class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400"
                        v-else
                    >
                        Are you sure you want to publish this?
                    </h3>
                    <Link @click="deleteroute">
                        <button
                            v-if="form.del_flg == 1"
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
