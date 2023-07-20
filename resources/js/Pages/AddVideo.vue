<script setup>
import { Head, Link, useForm  } from "@inertiajs/inertia-vue3";
import NavBar from "../Components/NavBar.vue";
import Header from "../Components/Header.vue";
import { ref } from "@vue/reactivity";
import { Inertia } from "@inertiajs/inertia";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/css/index.css";
import { provide } from "vue";

const props = defineProps({
    classDdata: Object,
    errors: {
        type: Object,
    },
    charLength:Object,
    lectureLength:Object
});

let vLength = props.charLength.filter((item)=>item.COLUMN_NAME == 'v_name')[0].CHARACTER_MAXIMUM_LENGTH
let descriptionLength = props.charLength.filter((item)=>item.COLUMN_NAME == "v_description")[0].CHARACTER_MAXIMUM_LENGTH
let storgelinkLength = props.charLength.filter((item)=>item.COLUMN_NAME == "v_storage_link")[0].CHARACTER_MAXIMUM_LENGTH
let l_nameLength = props.lectureLength.filter((item)=>item.COLUMN_NAME == "l_name")[0].CHARACTER_MAXIMUM_LENGTH
let l_storageLength = props.lectureLength.filter((item)=>item.COLUMN_NAME == "l_storage_link")[0].CHARACTER_MAXIMUM_LENGTH




const inputs = ref(0);

let keyUp = (event,index) =>{


}

const addInput = () => {
    inputs.value += 1;
};
const removeInput = (index) => {
    form.lecturename.splice(index, 1);
    form.storagelink.splice(index, 1);
    form.lecturelocation.splice(index, 1);
    form.lecturefile.splice(index, 1);
    document.getElementById("rfile" + (index + 1) ).value = "";
    document.getElementsByClassName('ftop'+(index + 1)).checked = false;
    document.getElementsByClassName("fbottom"+(index + 1) ).checked = true;
    document.getElementById("rfile" + (index + 1)).disabled = true;
     document.getElementById("stlink" + (index + 1)).disabled = false;
    document.getElementById("storagelocation" + (index + 1)).disabled = false;
    inputs.value -= 1;
};

const form = useForm({
    className: props.classDdata[0].c_name,
    classId: props.classDdata[0].id,
    videoName: '',
    description: '',
    date: null,
    storage: '',
    storagelocation: null,
    lecturename: [],
    storagelink: [],
    astoragelink: [],
    lecturelocation: [],
    lecturefile: [],
    alecturefile: [],
    input: inputs,
});


//loading Bars
let isloading =ref(false);

    let fullPage = ref(true)
const submit = () => {
    form.astoragelink = [];
    form.alecturefile = [];
    for (let i = 0; i < form.lecturename.length; i++) {
        if (form.storagelink[i]) {
            form.astoragelink.push(form.storagelink[i]);
        } else {
            form.astoragelink.push(null);
        }

        if (form.lecturefile[i]) {
            form.alecturefile.push(form.lecturefile[i]);
        } else {
            form.alecturefile.push(null);
        }
    }

    
    Inertia.post(route("addvideo.store"), form, {
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
function fileOn(obj) {
    console.log(obj);
    document.getElementById("rfile" + obj).disabled = true;
    document.getElementById("stlink" + obj).disabled = false;
    document.getElementById("storagelocation" + obj).disabled = false;
    document.getElementById("rfile" + obj).value = "";
    document.getElementById("stlink" + obj).required = true;
    document.getElementById("storagelocation" + obj).required = true;
}
function inputOn(obj) {
    console.log(obj);
    document.getElementById("stlink" + obj).disabled = true;
    document.getElementById("storagelocation" + obj).disabled = true;
    document.getElementById("rfile" + obj).disabled = false;
    document.getElementById("stlink" + obj).value = "";
    document.getElementById("storagelocation" + obj).value = "";
    document.getElementById("rfile" + obj).required = true;
}

window.history.forward();
const showMenu = ref(true);

provide("showMenu", showMenu);
</script>

<template>
    <Head title="Add Video" />
    <!-------------------- Navbar&header -------------------->
    <NavBar active=2> </NavBar>
    <Header headername="Add Video"  class="w-full pr-36 transition-all duration-500" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"/>

    <!---------------- body ----------------------->
    <div class="absolute h-auto w-full p-5 headercustomleft top-32 customblack transition-all duration-500 pr-36" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'">

        <div
            class="w-full h-auto p-8 relative bg-secondaryBackground rounded-xl flex flex-col items-center mb-4"
        >
        <loading v-model:active="isloading" :is-full-page="fullPage" :loader="'bars'" :color="'#000'" :width="'200'" :height="'300'"/>
            <form @submit.prevent="submit" class="w-full">
                <div class="w-full grid gap-6 mb-6 md:grid-cols-2">
                    <div class="">
                        <p class="text-white">Video Upload</p>

                        <!-- VIEDO PATH -->
                        <div class="ml-10 sm:w-full sm:ml-4 mt-5">
                            <label
                                for="classname"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Class Name</label
                            >
                            <input
                                v-model="form.className"
                                type="text"
                                id="classname"
                                class="focus:ring-white focus:border-white border-white text-white text-sm rounded-xl block w-5/6 bg-elementBackground p-2"
                                placeholder=""
                                disabled
                            />
                        </div>
                        <!-- Name -->
                        <div class="ml-10 sm:w-full sm:ml-4 mt-5">
                            <label
                                for="Name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Name</label
                            >
                            <input
                                v-model="form.videoName"
                                :maxlength="vLength"
                                type="text"
                                id="Name"
                                class="focus:ring-white focus:border-white border-white text-white text-sm rounded-xl block w-5/6 bg-elementBackground p-2"
                                placeholder=""
                                
                            />
                            <div class="flex float-right pr-28 text-xs text-whiteTextColor"
                                v-text="(vLength - form.videoName.length + ' words left')"></div>
                            <div v-if="errors.videoName" class="text-red-500">
                            {{ errors.videoName }}
                        </div>
                        </div>
                        <!-- Description -->
                        <div class="ml-10 sm:w-full sm:ml-4 mt-5">
                            <label
                                for="description"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Description</label
                            >
                            <textarea
                                v-model="form.description"
                                :maxlength="descriptionLength"
                                name=""
                                id="description"
                                class="resize-none focus:ring-white focus:border-white border-white text-white text-sm rounded-xl block w-5/6 bg-elementBackground p-2 h-40"
                            ></textarea>
                            <div class="flex float-right pr-28 text-xs text-whiteTextColor"
                                v-text="(descriptionLength - form.description.length + ' words left')"></div>
                            <div v-if="errors.description" class="text-red-500">
                            {{ errors.description }}
                        </div>
                        </div>
                        <!-- Date -->
                        <div class="ml-10 sm:w-full sm:ml-4 mt-5">
                            <label
                                for="Date"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Date</label
                            >
                            <input
                                v-model="form.date"
                                
                                type="date"
                                id="Date"
                                class="focus:ring-white focus:border-white border-white text-white text-sm rounded-xl block w-5/6 bg-elementBackground p-2"
                                placeholder=""
                            />
                            <div v-if="errors.date" class="text-red-500">
                            {{ errors.date }}
                        </div>
                        </div>
                        <!-- Storage Link -->
                        <div class="ml-10 sm:w-full sm:ml-4 mt-5">
                            <label
                                for="storage"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Storage Link</label
                            >
                            <input
                                v-model="form.storage"
                                :maxlength="storgelinkLength"
                                type="text"
                                id="storage"
                                class="focus:ring-white focus:border-white border-white text-white text-sm rounded-xl block w-5/6 bg-elementBackground p-2"
                                placeholder=""
                            />
                            <div class="flex float-right pr-28 text-xs text-whiteTextColor"
                                v-text="(storgelinkLength - form.storage.length + ' words left')"></div>
                            <div v-if="errors.storage" class="text-red-500">
                            {{ errors.storage }}
                        </div>
                        </div>
                        <!-- Storage Location -->
                        <div class="ml-10 sm:w-full sm:ml-4 mt-5">
                            <label
                                for="storagelocaton"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Storage location</label
                            >
                            <select
                                v-model="form.storagelocation"
                                name=""
                                id="storagelocaton"
                                class="focus:ring-white focus:border-white border-white text-white text-sm rounded-xl block w-5/6 bg-elementBackground p-2"
                            >
                                <option value="Local Database">
                                    Local Database
                                </option>
                                <option value="Youtube">Youtube</option>
                                <option value="Google Drive">
                                    Google Drive
                                </option>
                                <option value="Vimeo">Vimeo</option>
                            </select>
                            <div v-if="errors.storagelocation" class="text-red-500">
                            {{ errors.storagelocation }}
                        </div>
                        </div>
                    </div>
                    <!-- whiteline -->
                    <div
                        class="absolute left-1/2 sm:w-0 md:w-0.5 bg-white h-4/5"
                    ></div>
                    <div>
                        <p class="text-white pl-6">Lecture Upload</p>

                        <div class="pl-10 mt-5" id="create">
                            <button
                                class="py-2 px-5 text-whiteTextColor text-md bg-blueTextColor rounded-xl flex items-center"
                                @click="addInput"
                                type="button"
                                :class="{
                                'block' : inputs < 5,
                                'hidden' : inputs == 5
                                }"
                            >
                                <img
                                    src="../../../public/img/addlogo.png"
                                    alt=""
                                    class="w-5 h-5 pt-0.5"
                                /><span class="mx-2">Create Lecture</span>
                            </button>
                        </div>
                        <!-- Lecture Name -->
                        <div v-for="input in inputs" :key="input">
                            <div class="float-right">
                                <button
                                    type="button"
                                    @click="removeInput(input - 1)"
                                >
                                    <img
                                        src="../../../public/img/minus-circle.svg"
                                        alt=""
                                        class="w-8 h-8"
                                    />
                                </button>
                            </div>
                            <div class="sm:w-full sm:ml-4 mt-5 pl-7">
                                <label
                                    for="Lecturename"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                    >Lecture Name</label
                                >
                                <input
                                    v-model="form.lecturename[input - 1]"
                                    @keyup="keyUp(event,input-1)"
                                    type="text"
                                    id="Lecturename"
                                    :maxlength="l_nameLength"
                                    class="focus:ring-white focus:border-white border-white text-white text-sm rounded-xl block w-5/6 bg-elementBackground p-2"
                                    placeholder=""
                                    required
                                />
                               <div class="flex float-right pr-28 text-xs text-whiteTextColor" v-if="form.lecturename.length > input-1"
                                v-text="((l_nameLength - form.lecturename[input - 1].length ) + ' words left')"></div>
                            </div>

                            <!-- FILE -->
                            <div class="sm:w-full sm:ml-4 mt-5 pl-7">
                                <label
                                    :for="`rfile${input}`"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                    >File</label
                                >
                                <div class="flex flex-row">
                                    <input
                                        v-bind:class="{ disabled: dis == 1 }"
                                        @input="
                                            form.lecturefile[input - 1] =
                                                $event.target.files
                                        "
                                        :id="`rfile${input}`"
                                        type="file"
                                        disabled
                                        class="block w-5/6 h-9 border rounded-xl cursor-pointer file:h-full file:rounded-l-sm file:border-0 file:mr-1.5 text-white"
                                    />
                                    <input
                                        type="radio"
                                        id="default-radio-1"
                                        class="ml-4 mt-2 ftop"
                                        :name="input"
                                        @click="inputOn(input)"
                                        required
                                    />
                                </div>
                            </div>
                            <div class="flex mt-5 justify-center">
                                <hr class="h-px w-3/12 mt-2" />
                                <p class="text-white mx-5">OR</p>
                                <hr class="h-px w-3/12 mt-2" />
                            </div>
                            <!-- storageLink -->
                            <div class="sm:w-full sm:ml-4 mt-5 pl-7">
                                <label
                                    :for="`stlink${input}`"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                    >Storage Link</label
                                >
                               
                                <input
                                    v-model="form.storagelink[input - 1]"
                                    type="text"
                                    :id="`stlink${input}`"
                                    :maxlength="l_storageLength"
                                    class="focus:ring-white focus:border-white border-white text-white text-sm rounded-xl block w-5/6 bg-elementBackground p-2"
                                    placeholder=""
                                    required
                                />
                                <div class="flex float-right pr-28 text-xs text-whiteTextColor" v-if="form.storagelink.length > input-1"
                                v-text="(l_storageLength - form.storagelink[input - 1].length  + ' words left')"></div>
                            
                              
                               
                            </div>
                            
                            <!-- Storage Location -->
                            <div class="pl-7 sm:w-full sm:ml-4 mt-5">
                                <label
                                    :for="`storagelocation${input}`"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                    >Storage location</label
                                >
                                 <div class="flex flex-row">
                                <select
                                    v-model="form.lecturelocation[input - 1]"
                                    name=""
                                    :id="`storagelocation${input}`"
                                    required
                                    class="focus:ring-white focus:border-white border-white text-white text-sm rounded-xl block w-5/6 bg-elementBackground p-2"
                                >
                                    <option value="Local Database">
                                        Local Database
                                    </option>
                                    <option value="Youtube">Youtube</option>
                                    <option value="Google Drive">
                                        Google Drive
                                    </option>
                                    <option value="Vimeo">Vimeo</option>
                                </select>
                                  <input
                                    checked
                                    type="radio"
                                    id="default-radio-1"
                                    class="ml-4 mt-2 fbottom"
                                    :name="input"
                                    @click="fileOn(input)"
                                />
                                 </div>
                            </div>
                            <hr
                                class="mt-10 ml-10 h-px w-11/12 bg-gray-200 border-0"
                                id="asdas"
                            />
                        </div>
                    </div>
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
        <div class="absolute bottom-0 left-0 transition-all duration-500" :class="showMenu ? 'left-4' : 'left-0'">
            <button>
                <a
                    href="/class"
                    class="underline underline-offset-4 hidden md:block text-white"
                    >back</a
                >
            </button>
        </div>
    </div>
</template>
<style scoped>
#Date{
    color-scheme: dark;
}

</style>
