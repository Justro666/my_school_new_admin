<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import NavBar from "../Components/NavBar.vue";
import Header from "../Components/Header.vue";
import { ref } from "@vue/reactivity";
import { Inertia } from "@inertiajs/inertia";
import { provide} from "vue";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/css/index.css";

const props = defineProps({
    videoData: Object,
    classDdata: Object,
    errors: {
        type: Object,
    },
    classId: Object,
    charLength:Object,
    lectureLength:Object
});
console.log(props.videoData);

let vLength = props.charLength.filter((item)=>item.COLUMN_NAME == 'v_name')[0].CHARACTER_MAXIMUM_LENGTH
let descriptionLength = props.charLength.filter((item)=>item.COLUMN_NAME == "v_description")[0].CHARACTER_MAXIMUM_LENGTH
let storgelinkLength = props.charLength.filter((item)=>item.COLUMN_NAME == "v_storage_link")[0].CHARACTER_MAXIMUM_LENGTH
let l_nameLength = props.lectureLength.filter((item)=>item.COLUMN_NAME == "l_name")[0].CHARACTER_MAXIMUM_LENGTH
let l_storageLength = props.lectureLength.filter((item)=>item.COLUMN_NAME == "l_storage_link")[0].CHARACTER_MAXIMUM_LENGTH

// let descriptionLength = ref(props.charLength[4].CHARACTER_MAXIMUM_LENGTH);
// let storgelinkLength = ref(props.charLength[5].CHARACTER_MAXIMUM_LENGTH);
// let l_nameLength =ref(props.lectureLength[0].CHARACTER_MAXIMUM_LENGTH);
// let l_storageLength =ref(props.lectureLength[1].CHARACTER_MAXIMUM_LENGTH);


const inputs = ref(props.videoData.t_lecture_note.length);
const File = ref(
    "/storage/" +
        props.videoData.t_lecture_note.map((item) => item.l_storage_link)
);

const addInput = () => {
    inputs.value += 1;
};
const removeInput = (index) => {
    
    form.lecturename.splice(index, 1);
    form.storagelink.splice(index, 1);
    form.lecturelocation.splice(index, 1);
    form.lecturefile.splice(index, 1);
    form.astoragelink.splice(index,1);
    form.alecturefile.splice(index,1);
    form.lectureid.splice(index,1);
    form.alectureid.splice(index,1);
    // form.lecturelocation[index-1] = "";
    // form.storagelink[index-1] = "";
    form.lecturefile[index-1] = "";
    console.log();
    inputs.value -= 1;

};

const form = useForm({
    id: props.videoData.id,
    className: props.classDdata.c_name,
    classId: props.videoData,
    videoName: props.videoData.v_name,
    description: props.videoData.v_description,
    date: props.videoData.v_date,
    storage: props.videoData.v_storage_link,
    storagelocation: props.videoData.v_storage_location,
    lectureid:props.videoData.t_lecture_note.map((item) =>item.id),
    lecturename: props.videoData.t_lecture_note.map((item) => item.l_name),
    storagelink: props.videoData.t_lecture_note.map(
        (item) => item.l_storage_link
    ),
    astoragelink: [],
    lecturelocation: [],
    alectureid:[],
    lecturefile: [],
    alecturefile: [],
    input: inputs,
    _method: "put",
});



for (let index = 0; index < props.videoData.t_lecture_note.length; index++) {
    if (props.videoData.t_lecture_note[index].l_storage_location == "") {
        form.lecturelocation[index] = "Local Database";
    } else
        form.lecturelocation[index] =
            props.videoData.t_lecture_note[index].l_storage_location;
}



//loading Bars
let isloading = ref(false);
let fullPage = ref(true);

const deleteVideo = () => {
    Inertia.delete(route("addvideo.destroy", form.id), {
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
const submit = () => {
    form.astoragelink = [];
    form.alecturefile = [];
    form.alectureid = [];
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
        if(form.lectureid[i]){
            form.alectureid.push(form.lectureid[i]);
        } else{
            form.alectureid.push(null);
        }
    }

    Inertia.post(route("addvideo.update",form.id), form, {
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
    document.getElementById("rfile" + obj).disabled = true;
    document.getElementById("stlink" + obj).disabled = false;
    document.getElementById("storagelocation" + obj).disabled = false;
    document.getElementById("rfile" + obj).value = "";
    document.getElementById("stlink" + obj).required = true;
    document.getElementById("storagelocation" + obj).required = true;
    form.lecturelocation[obj-1] = "";
    form.storagelink[obj-1] = "";
    form.lecturefile[obj-1]="";
}
function inputOn(obj) {
    document.getElementById("stlink" + obj).disabled = true;
    document.getElementById("storagelocation" + obj).disabled = true;
    document.getElementById("rfile" + obj).disabled = false;
    document.getElementById("stlink" + obj).value = "";
    document.getElementById("storagelocation" + obj).value = "";
    document.getElementById("rfile" + obj).required = true;
    form.lecturelocation[obj-1] = "";
    form.storagelink[obj-1] = "";
    form.lecturefile[obj-1]="";
}
let pop = ref(0);
const popup = () => {
    pop.value = 1;
};
let delete_pop = ref(1);
const delete_popup = () => {
    pop.value = 0;

};

window.history.forward();
const showMenu = ref(true);

provide("showMenu", showMenu);
</script>

<template>
    <Head title="Edit Admin" />
    <!-------------------- Navbar&header -------------------->
    <NavBar active="2"> </NavBar>
    <Header headername="Edit Video" class="w-full pr-36 transition-all duration-500" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"/>

    <!---------------- body ----------------------->
    <div class="absolute h-auto w-full p-5 headercustomleft top-32 customblack transition-all duration-500 pr-36" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'">
        <loading
            v-model:active="isloading"
            :is-full-page="fullPage"
            :loader="'bars'"
            :color="'#000'"
            :width="'200'"
            :height="'300'"
        />
        <div
            class="w-full h-auto p-8 relative bg-secondaryBackground rounded-xl flex flex-col items-center"
        >
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
                                :disabled="pop == 1"
                                type="text"
                                :maxlength="vLength"
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
                                :disabled="pop == 1"
                                name=""
                                id="description"
                                :maxlength="descriptionLength"
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
                                :disabled="pop == 1"
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
                                :disabled="pop == 1"
                                type="text"
                                :maxlength="storgelinkLength"
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
                                :disabled="pop == 1"
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
                            <div
                                v-if="errors.storagelocation"
                                class="text-red-500"
                            >
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

                        <div class="pl-10 mt-5">
                            <button
                                class="py-2 px-5 text-whiteTextColor text-md bg-blueTextColor rounded-xl flex items-center"
                                @click="addInput"
                                :disabled="pop == 1"
                                type="button"
                                :class="{
                                    block: inputs < 5,
                                    hidden: inputs == 5,
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
                                    :disabled="pop == 1"
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
                                    :disabled="pop == 1"
                                    v-model="form.lecturename[input - 1]"
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
                                        :id="`rfile${input}`"
                                        @input="
                                            form.lecturefile[input - 1] =
                                                $event.target.files
                                        "
                                        disabled
                                        type="file"
                                        class="block w-5/6 h-9 border rounded-xl cursor-pointer file:h-full file:rounded-l-sm file:border-0 file:mr-1.5 text-white"
                                    />
                                    <input
                                        :disabled="pop == 1"
                                        type="radio"
                                        id="default-radio-1"
                                        class="ml-4 mt-2"
                                        :name="input"
                                        @click="inputOn(input)"
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
                                        type="url"
                                        :id="`stlink${input}`"
                                        class="focus:ring-white focus:border-white border-white text-white text-sm rounded-xl block w-5/6 bg-elementBackground p-2"
                                        placeholder=""
                                        required
                                        :maxlength="l_storageLength"
                                        :disabled="pop == 1"
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
                                    :disabled="pop == 1"
                                    name=""
                                    :id="`storagelocation${input}`"
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
                                        class="ml-4 mt-2"
                                        :name="input"
                                        @click="fileOn(input)"
                                        :disabled="pop == 1"
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
                <div class="flex justify-between py-8">
                    <button
                        :disabled="pop == 1"
                        type="button"
                        class="py-2 px-5 text-whiteTextColor text-md bg-redTextColor rounded-xl flex items-center"
                        @click="popup"
                    >
                        <img
                            src="../../../public/img/delete.png"
                            alt=""
                            class="w-5 h-5 pt-0.5"
                        />
                        <span class="mx-2">Delete</span>
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
        </div>
        <div class="w-14 ml-1 py-10">
            <button>
                <a
                    href="/class"
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
                    >
                        Are you sure you want to Delete this?
                    </h3>

                    <Link @click="deleteVideo">
                        <button
                            type="button"
                            class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2"
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
<style scoped>
#Date {
    color-scheme: dark;
}
</style>
