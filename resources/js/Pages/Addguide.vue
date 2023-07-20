<script setup>
import NavBar from "../Components/NavBar.vue";
import Header from "../Components/Header.vue";
import Pagination from "../Components/Pagination.vue";
import { Inertia } from "@inertiajs/inertia";
import { createVNode, ref , provide } from "vue";
import { useForm,Head } from "@inertiajs/inertia-vue3";
import { Link } from "@inertiajs/inertia-vue3";
import Toolsbar from "../Components/Toolsbar.vue";
import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/css/index.css';

const props = defineProps({
    errors: {
        type: Object,
    },
    charLength:Object,
    stepLength:Object
});



let titleLength = props.charLength.filter((item)=>item.COLUMN_NAME == 'g_title')[0].CHARACTER_MAXIMUM_LENGTH
let steptLength = props.stepLength.filter((item)=>item.COLUMN_NAME == 'step_title')[0].CHARACTER_MAXIMUM_LENGTH
let descLength = props.stepLength.filter((item)=>item.COLUMN_NAME == "step_description")[0].CHARACTER_MAXIMUM_LENGTH;


const inputs = ref(1);
const addInput = () => {
    inputs.value += 1;
};


let imageFile = ref([]);
let input = null;

const showImagePreview = (event) =>{
    input = event.target;
   if (input.files && input.files[0]) {
    let reader = new FileReader();
    const file =event.target.files[0];
    imageFile.value[event.target.id] = URL.createObjectURL(file);

    reader.onload = (e) => {
    imageFile.value.push(e.target.id.result);
    // imageFile.value[event.target.id] = URL.createObjectURL(file);
    // console.log(imageFile);
   }
   reader.readAsDataURL(input.files[0]);
}
}

const removeInput = (index) => {
    form.steptitle.splice(index, 1);
    form.description.splice(index, 1);
    form.step_file.splice(index, 1);
    imageFile.value.splice(index,1);
    // console.log();
    inputs.value -= 1;

console.log(imageFile.value);
};


const form = useForm({
    guidetitle: '',
    steptitle: [],
    description: [],
    step_file: [],
    input: inputs,
});
//loading Bars
let isloading =ref(false);

let fullPage = ref(true)

const submit = () => {

    Inertia.post(route("guideTool.store"), form, {
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

window.history.forward();
const showMenu = ref(true);

provide('showMenu', showMenu);
</script>

<template>
 <Head title="Add Guide" />
    <NavBar active=8> </NavBar>
    <Header headername="Guide" class="w-full pr-36 transition-all duration-500" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"/>
    <loading v-model:active="isloading" :is-full-page="fullPage" :loader="'bars'" :color="'#000'" :width="'200'" :height="'300'"/>
    <div class="absolute h-full w-full p-5 headercustomleft top-32 customblack transition-all duration-500 pr-36" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'">
        <Toolsbar active="5" />
        
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
                        class="focus:ring-white focus:border-white border-white text-white text-sm rounded-xl block w-11/12 bg-elementBackground p-2"
                    />
                    <div class="flex float-right pr-28 text-xs text-whiteTextColor"
                                v-text="(titleLength - form.guidetitle.length + ' words left')"></div>
                    <div v-if="errors.guidetitle" class="text-red-500">
                            {{ errors.guidetitle }}
                        </div>
                </div>

                <!-- Step  -->
                <div v-for="input in inputs" :key="input">
                    <div id="step">
                        <div class="flex flex-row justify-between px-44 w-full">    
                            <p class="text-xl text-white pt-6 font-bold pr-80 w-full    ">
                                Step {{ input }}
                            </p>
                            <div
                                class="pt-6 pr-20"
                                @click="removeInput(input - 1)"
                                v-show="input > 1"
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

                        <div class="pt-8 w-full px-44">
                            <label
                                for="steptitle"
                                class="text-white text-md block"
                            >
                                Step Title</label
                            >
                            <input
                                v-model="form.steptitle[input - 1]"
                               
                                type="text"
                                id="steptitle"
                                :maxlength="steptLength"
                                class="focus:ring-white focus:border-white border-white text-white text-sm rounded-xl block w-11/12 bg-elementBackground p-2"
                            />
                             <div class="flex float-right pr-28 text-xs text-whiteTextColor" v-if="form.steptitle.length > input-1"
                                v-text="((steptLength - form.steptitle[input - 1].length ) + ' words left')"></div>
                            <div v-if="errors.steptitle" class="text-red-500">
                            {{ errors.steptitle }}
                        </div>
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
                                :maxlength="descLength"
                                id="Description"
                                class="h-48 w-11/12 resize-none rounded-xl bg-secondaryBackground text-whiteTextColor focus:outline-0 focus:ring-white focus:border-white border-white"
                            >
                            </textarea>
                            <div class="flex float-right pr-28 text-xs text-whiteTextColor" v-if="form.description.length > input-1"
                                v-text="((descLength - form.description[input - 1].length ) + ' words left')"></div>
                            <div v-if="errors.description > input-1" class="text-red-500">
                            {{ errors.description }}
                        </div>
                        </div>
                        <!-- Dropzone -->
                        <div class="mt-5  px-44">
                            <label for="" class="text-white text-md"
                                >File</label
                            >
                        </div>

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
                                    <div class="flex absolute w-full  h-full">
                                        <img
                                            :src="imageFile[input - 1]"
                                            :id="`img${input}`"
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
                                    
                                    :id="input - 1"
                                    type="file"
                                    :fileid="input - 1"
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
                            {{ errors.step_file}}
                        </div>
                        </div>
                         

                        <!-- line -->
                        <hr
                            class="mt-14 h-px w-11/12 bg-gray-200 border-0 relative left-4"
                            id="asdas"
                        />
                    </div>
                </div>

                <div
                    class="text-white bg-blueTextColor w-1/6 rounded-xl text-sm px-5 py-2.5 mt-9 flex flex-row justify-center items-center space-x-3"
                    @click="addInput"
                     :class="{
                                'block' : inputs < 10,
                                'hidden' : inputs == 10
                               }"
                >
                    <img
                        src="../../../public/img/addlogo.png"
                        alt=""
                        class="w-5 h-5 pt-0.5"
                    />
                    <button type="button">Create New Step</button>
                </div>

                <div class="flex float-right py-8">
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
        </div>
        <div class="w-14 ml-1 py-10">
            <button>
                <a
                    href="/guideTool"
                    class="underline underline-offset-4 hidden md:block text-white"
                    >back</a
                >
            </button>
        </div>
    </div>
</template>

<script>
</script>
<style></style>
