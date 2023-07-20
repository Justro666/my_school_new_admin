<script setup>
import NavBar from "../Components/NavBar.vue";
import Header from "../Components/Header.vue";
import Notisuccuss from "../Components/Notisuccess.vue";
import Pagination from "../Components/Pagination.vue";
import { Inertia } from "@inertiajs/inertia";
import { ref ,provide } from "vue";
import { useForm, Head } from "@inertiajs/inertia-vue3";

import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/css/index.css";

const props = defineProps({
    mysch: Object,
    public: Object,
    errors: {
        type: Object,
    },
    sessionId: {
        Object,
    },
    charLength: Object,
    pcharLength: Object,
});

let siteLength = props.charLength.filter(
    (item) => item.COLUMN_NAME == "sitename"
)[0].CHARACTER_MAXIMUM_LENGTH;
let facebookLength = props.charLength.filter(
    (item) => item.COLUMN_NAME == "facebook_link"
)[0].CHARACTER_MAXIMUM_LENGTH;
let youtube1Length = props.charLength.filter(
    (item) => item.COLUMN_NAME == "youtube_link1"
)[0].CHARACTER_MAXIMUM_LENGTH;
let youtube2Length = props.charLength.filter(
    (item) => item.COLUMN_NAME == "youtube_link2"
)[0].CHARACTER_MAXIMUM_LENGTH;
let youtube3Length = props.charLength.filter(
    (item) => item.COLUMN_NAME == "youtube_link3"
)[0].CHARACTER_MAXIMUM_LENGTH;
let messengerLength = props.charLength.filter(
    (item) => item.COLUMN_NAME == "messanger_link1"
)[0].CHARACTER_MAXIMUM_LENGTH;
let addressLength = props.charLength.filter(
    (item) => item.COLUMN_NAME == "address"
)[0].CHARACTER_MAXIMUM_LENGTH;
let emailLength = props.charLength.filter(
    (item) => item.COLUMN_NAME == "email"
)[0].CHARACTER_MAXIMUM_LENGTH;

let psiteLength = props.pcharLength.filter(
    (item) => item.COLUMN_NAME == "sitename"
)[0].CHARACTER_MAXIMUM_LENGTH;
let phonelength = props.pcharLength.filter(
    (item) => item.COLUMN_NAME == "phones"
)[0].CHARACTER_MAXIMUM_LENGTH;
let copyLength = props.pcharLength.filter(
    (item) => item.COLUMN_NAME == "copyright"
)[0].CHARACTER_MAXIMUM_LENGTH;
let pfacebookLength = props.pcharLength.filter(
    (item) => item.COLUMN_NAME == "facebook_link"
)[0].CHARACTER_MAXIMUM_LENGTH;
let pyoutube1Length = props.pcharLength.filter(
    (item) => item.COLUMN_NAME == "youtube_link1"
)[0].CHARACTER_MAXIMUM_LENGTH;
let pyoutube2Length = props.pcharLength.filter(
    (item) => item.COLUMN_NAME == "youtube_link2"
)[0].CHARACTER_MAXIMUM_LENGTH;

const form = useForm({
    logo: null,
    favicon: null,
    sitename: props.mysch[0].sitename,
    facebook: props.mysch[0].facebook_link,
    youtube1: props.mysch[0].youtube_link1,
    youtube2: props.mysch[0].youtube_link2,
    youtube3: props.mysch[0].youtube_link3,
    messenger: props.mysch[0].messanger_link1,
    address: props.mysch[0].address,
    email: props.mysch[0].email,
});
// let siteLength = ref(props.charLength[0].CHARACTER_MAXIMUM_LENGTH);
// let facebookLength = ref(props.charLength[1].CHARACTER_MAXIMUM_LENGTH);
// let youtube1Length = ref(props.charLength[2].CHARACTER_MAXIMUM_LENGTH);
// let youtube2Length = ref(props.charLength[3].CHARACTER_MAXIMUM_LENGTH);
// let youtube3Length = ref(props.charLength[4].CHARACTER_MAXIMUM_LENGTH);
// let messengerLength = ref(props.charLength[5].CHARACTER_MAXIMUM_LENGTH);
// let phonelength = ref(props.pcharLength[18].CHARACTER_MAXIMUM_LENGTH);
// let copyLength = ref(props.pcharLength[1].CHARACTER_MAXIMUM_LENGTH);
// let addressLength = ref(props.pcharLength[1].CHARACTER_MAXIMUM_LENGTH);
// let emailLength = ref(props.pcharLength[1].CHARACTER_MAXIMUM_LENGTH);

let mysc_logoimageFile = ref(props.mysch[0].logo);

let mysc_input = null;
const mysc_logoshowImage = (event) => {
    mysc_input = event.target;
    if (mysc_input.files && mysc_input.files[0]) {
        const file = event.target.files[0];
        mysc_logoimageFile.value = URL.createObjectURL(file);
    }
};
let my_faviconfile = ref(props.mysch[0].favicon);
let mysc_faviconinput = null;
const mysc_faviconshowImage = (event) => {
    mysc_faviconinput = event.target;
    if (mysc_faviconinput.files && mysc_faviconinput.files[0]) {
        const file = event.target.files[0];
        my_faviconfile.value = URL.createObjectURL(file);
    }
};

//loading Bars
let isloading = ref(false);
let fullPage = ref(true);

const submit_mysc = () => {
    Inertia.post("setting/upload", form, {
        onError: (data) => {

            // inputField.value.focus();
        },
        onSuccess: (data) => {
            result.value = data.props.flash.message;
            showNoti.value = true;
            setTimeout(() => {
                showNoti.value = false;

            }, 2000);
        },
        onStart: (data) => {
            isloading.value = true;
        },
        onFinish: () => {
            isloading.value = false;
        },
    });
};

const form_public = useForm({
    logos: null,
    favicons: null,
    darklogo: null,
    sitenames: props.public[0].sitename,
    facebook: props.public[0].facebook_link,
    youtube1: props.public[0].youtube_link1,
    youtube2: props.public[0].youtube_link2,
    messenger: props.public[0].messanger_link1,
    copyright: props.public[0].copyright,
    phone: props.public[0].phones,
});

let pub_logoimageFile = ref(props.public[0].logo);

let pub_logoinput = null;
const pub_logoshowImage = (event) => {
    pub_logoinput = event.target;
    if (pub_logoinput.files && pub_logoinput.files[0]) {
        const file = event.target.files[0];
        pub_logoimageFile.value = URL.createObjectURL(file);
    }
};
let pub_darklogoimageFile = ref(props.public[0].dark_logo);

let pub_darklogoinput = null;
const pub_darklogoshowImage = (event) => {
    pub_darklogoinput = event.target;
    if (pub_darklogoinput.files && pub_darklogoinput.files[0]) {
        const file = event.target.files[0];
        pub_darklogoimageFile.value = URL.createObjectURL(file);
    }
};

let pub_faviconimageFile = ref(props.public[0].favicon);
let pub_faviconinput = null;
const pub_faviconshowImage = (event) => {
    pub_faviconinput = event.target;
    if (pub_faviconinput.files && pub_faviconinput.files[0]) {
        const file = event.target.files[0];
        pub_faviconimageFile.value = URL.createObjectURL(file);
    }
};


const submit_public = () => {
    Inertia.post("setting/upload_public", form_public, {
        onError: (data) => {

            // inputField.value.focus();
        },
        onSuccess: (data) => {
            showNoti.value = true;
            setTimeout(() => {
                showNoti.value = false;

            }, 2000);
        },
        onStart: (data) => {
            isloading.value = true;
        },
        onFinish: () => {
            isloading.value = false;
        },
    });

};

// TabChange
let openTab = ref(1);

let showNoti = ref(false);
let result = ref("");

window.history.forward();
const showMenu = ref(true);

provide('showMenu', showMenu);
</script>

<template>
    <Head title="Setting" />
    <!-------------------- Navbar&header -------------------->
    <NavBar active="7" mysch></NavBar>
    <Header headername="Settings" class="w-full pr-36 transition-all duration-500" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"/>
    <loading
        v-model:active="isloading"
        :is-full-page="fullPage"
        :loader="'bars'"
        :color="'#000'"
        :width="'200'"
        :height="'300'"
    />
    <div
        class="absolute top-32 headercustomleft w-full h-auto bg-primaryBackground items-center flex flex-col pb-4 customblack transition-all duration-500 pr-36" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"
    >
        <div v-if="$page.props.flash.message" class="absolute z-50 right-64">
            <Notisuccuss v-if="showNoti" :result="result" />
        </div>

        <div
            class="flex items-center w-11/12 mt-24 bg-primaryBackground flex-row justify-center"
        >
            <div
                class="w-4/6 items-center text-center rounded-tl-xl bg-primaryBackground text-white p-4 cursor-pointer tab hover:bg-elementBackground"
                id="tab-1"
                @click="openTab = 1"
                :class="{
                    'bg-primaryBackground': openTab != 1,
                    'bg-elementBackground': openTab == 1,
                }"
            >
                SiteMaster Myschool
            </div>
            <div
                class="w-4/6 items-center text-center rounded-tr-xl bg-primaryBackground hover:bg-elementBackground text-white p-4 cursor-pointer tab"
                id="tab-2"
                @click="openTab = 2"
                v-bind:class="{
                    'bg-primaryBackground': openTab != 2,
                    'bg-elementBackground': openTab == 2,
                }"
            >
                SiteMaster Public
            </div>
        </div>
        <div
            class="w-11/12 h-auto bg-elementBackground text-white tab-pannel-2 rounded-b-xl"
            id="tab-pannel-1"
            v-bind:class="{ hidden: openTab != 1, block: openTab == 1 }"
        >
  
            <!-- Myschool -->
            <form
                class="mt-6"
                @submit.prevent="submit_mysc"
                enctype="multipart/form-data"
            >
             
                <div class="grid mb-6 md:grid-cols-2">
                    
                    <!-- LOGO -->
                    <div class="ml-8 my-5">
                        <div class="ml-10 my-5 sm:w-full sm:ml-4">
                            <label
                                for=""
                                class="block mb-2 text-md font-medium text-gray-900 dark:text-white"
                                >Logo</label
                            >
                            <label
                                for="dropzone-file"
                                class="flex flex-col items-center justify-center w-5/6 h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                            >
                                <div
                                    class="flex flex-col items-center justify-center pt-5 pb-6"
                                >
                                    <div class="absolute w-36">
                                        <img
                                            :src="mysc_logoimageFile"
                                            alt=""
                                            class="items-center"
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
                                    @input="form.logo = $event.target.files[0]"
                                    @change="mysc_logoshowImage($event)"
                                    accept="image/*"
                                    id="dropzone-file"
                                    type="file"
                                    class="hidden"
                                />
                            </label>
                            <div v-if="errors.logo" class="text-red-500">
                                {{ errors.logo }}
                            </div>
                        </div>

                        <!-- Fav Icon -->
                        <div class="ml-10 my-5 sm:w-full sm:ml-4">
                            <label
                                for="favicon"
                                class="block mb-2 text-md font-medium text-gray-900 dark:text-white"
                                >Favicon</label
                            >
                            <label
                                for="favicon"
                                class="flex flex-col items-center justify-center w-5/6 h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                            >
                                <div
                                    class="flex flex-col items-center justify-center pt-5 pb-6"
                                >
                                    <div class="absolute w-36">
                                        <img
                                            :src="my_faviconfile"
                                            alt=""
                                            class="items-center"
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
                                    @input="
                                        form.favicon = $event.target.files[0]
                                    "
                                    @change="mysc_faviconshowImage"
                                    accept="image/*"
                                    id="favicon"
                                    type="file"
                                    class="hidden"
                                />
                            </label>

                            <!-- <label
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                    for="file_input"
                                    >Fav Icon</label
                                >
                                <input
                                    
                                    class="block w-5/6 h-9 border rounded-xl cursor-pointer file:h-full file:rounded-l-sm file:border-0 file:mr-1.5"
                                    id="file_input"
                                    type="file"
                                /> -->
                            <div v-if="errors.favicon" class="text-red-500">
                                {{ errors.favicon }}
                            </div>
                        </div>
                        <!-- Site Name -->
                        <div class="ml-10 my-5 sm:w-full sm:ml-4">
                            <label
                                for="Site_name"
                                class="block mb-2 text-md font-medium text-gray-900 dark:text-white"
                                >Site Name</label
                            >
                            <input
                                v-model="form.sitename"
                                type="text"
                                id="Site_name"
                                :maxlength="siteLength"
                                class="focus:ring-white focus:border-white border-white text-white text-sm rounded-xl block w-5/6 bg-elementBackground"
                                placeholder=""
                            />
                            <div
                                class="flex float-right pr-32 text-xs text-whiteTextColor"
                                v-text="
                                    siteLength -
                                    form.sitename.length +
                                    ' words left'
                                "
                            ></div>
                            <div v-if="errors.sitename" class="text-red-500">
                                {{ errors.sitename }}
                            </div>
                        </div>
                        
                    </div>
                          
                    <div class="cl2 my-5">
                              <!-- White Line -->
                        <div
                        class=" absolute  sm:w-0 md:w-0.5 h-4/6 bg-white"
                    ></div>
                    <div class=" pl-10">
                        <!-- FaceBook -->
                        <div class="ml-10 my-5 sm:w-full sm:ml-4">
                            <label
                                for="facebook"
                                class="block mb-2 text-md font-medium text-gray-900 dark:text-white"
                                >Facebook</label
                            >
                            <input
                                v-model="form.facebook"
                                type="text"
                                :maxlength="facebookLength"
                                id="facebook"
                                class="focus:ring-white focus:border-white border-white text-sm rounded-xl block w-5/6 bg-elementBackground p-2 text-white"
                                placeholder=""
                            />
                            <div
                                class="flex float-right pr-32 text-xs text-whiteTextColor"
                                v-text="
                                    facebookLength -
                                    form.facebook.length +
                                    ' words left'
                                "
                            ></div>
                            <div v-if="errors.facebook" class="text-red-500">
                                {{ errors.facebook }}
                            </div>
                        </div>
                        <!-- Messenger -->
                        <div class="ml-10 my-5 sm:w-full sm:ml-4">
                            <label
                                for="Messenger"
                                class="block mb-2 text-md font-medium text-gray-900 dark:text-white"
                                >Messenger</label
                            >
                            <input
                                v-model="form.messenger"
                                :maxlength="messengerLength"
                                type="text"
                                id="Messenger"
                                class="focus:ring-white focus:border-white border-white text-white text-sm rounded-xl block w-5/6 bg-elementBackground p-2"
                                placeholder=""
                            />
                            <div
                                class="flex float-right pr-32 text-xs text-whiteTextColor"
                                v-text="
                                    messengerLength -
                                    form.messenger.length +
                                    ' words left'
                                "
                            ></div>
                            <div v-if="errors.messenger" class="text-red-500">
                                {{ errors.messenger }}
                            </div>
                        </div>
                        <!-- YouTube1 -->
                        <div class="ml-10 my-5 sm:w-full sm:ml-4">
                            <label
                                for="youtube1"
                                class="block mb-2 text-md font-medium text-gray-900 dark:text-white"
                                >YouTube 1</label
                            >
                            <input
                                v-model="form.youtube1"
                                type="text"
                                id="youtube1"
                                :maxlength="youtube1Length"
                                class="focus:ring-white focus:border-white border-white text-white text-sm rounded-xl block w-5/6 bg-elementBackground p-2"
                                placeholder=""
                            />
                            <div
                                class="flex float-right pr-32 text-xs text-whiteTextColor"
                                v-text="
                                    youtube1Length -
                                    form.youtube1.length +
                                    ' words left'
                                "
                            ></div>
                            <div v-if="errors.youtube1" class="text-red-500">
                                {{ errors.youtube1 }}
                            </div>
                        </div>
                        <!-- Youtube2 -->
                        <div class="ml-10 my-5 sm:w-full sm:ml-4">
                            <label
                                for="youtube3"
                                class="block mb-2 text-md font-medium text-gray-900 dark:text-white"
                                >YouTube 2</label
                            >
                            <input
                                v-model="form.youtube2"
                                type="text"
                                :maxlength="youtube2Length"
                                id="youtube3"
                                class="focus:ring-white focus:border-white border-white text-white text-sm rounded-xl block w-5/6 bg-elementBackground p-2"
                                placeholder=""
                            />
                            <div
                                class="flex float-right pr-32 text-xs text-whiteTextColor"
                                v-text="
                                    youtube2Length -
                                    form.youtube2.length +
                                    ' words left'
                                "
                            ></div>
                            <div v-if="errors.youtube2" class="text-red-500">
                                {{ errors.youtube2 }}
                            </div>
                        </div>
                        <!-- Youtube3 -->
                        <div class="ml-10 my-5 sm:w-full sm:ml-4">
                            <label
                                for="youtube3"
                                class="block mb-2 text-md font-medium text-gray-900 dark:text-white"
                                >YouTube 3</label
                            >
                            <input
                                v-model="form.youtube3"
                                type="text"
                                :maxlength="youtube3Length"
                                id="youtube3"
                                class="focus:ring-white focus:border-white border-white text-white text-sm rounded-xl block w-5/6 bg-elementBackground p-2"
                                placeholder=""
                            />
                            <div
                                class="flex float-right pr-32 text-xs text-whiteTextColor"
                                v-text="
                                    youtube3Length -
                                    form.youtube3.length +
                                    ' words left'
                                "
                            ></div>
                            <div v-if="errors.youtube3" class="text-red-500">
                                {{ errors.youtube3 }}
                            </div>
                        </div>
                        <!-- email -->
                        <div class="ml-10 my-5 sm:w-full sm:ml-4">
                            <label
                                for="email"
                                class="block mb-2 text-md font-medium text-gray-900 dark:text-white"
                                >Email</label
                            >
                            <input
                                v-model="form.email"
                                type="text"
                                id="email"
                                :maxlength="emailLength"
                                class="focus:ring-white focus:border-white border-white text-white text-sm rounded-xl block w-5/6 bg-elementBackground p-2"
                                placeholder=""
                            />

                            <div
                                class="flex float-right pr-32 text-xs text-whiteTextColor"
                                v-text="
                                    emailLength -
                                    form.email.length +
                                    ' words left'
                                "
                            ></div>
                            <div v-if="errors.email" class="text-red-500">
                                {{ errors.email }}
                            </div>
                        </div>
                        <!-- Address -->
                        <div class="ml-10 my-5 sm:w-full sm:ml-4">
                            <label
                                for="address"
                                class="block mb-2 text-md font-medium text-gray-900 dark:text-white"
                                >Address</label
                            >
                            <input
                                v-model="form.address"
                                type="text"
                                id="address"
                                :maxlength="addressLength"
                                class="focus:ring-white focus:border-white border-white text-white text-sm rounded-xl block w-5/6 bg-elementBackground p-2"
                                placeholder=""
                            />
                            <div
                                class="flex float-right pr-32 text-xs text-whiteTextColor"
                                v-text="
                                    addressLength -
                                    form.address.length +
                                    ' words left'
                                "
                            ></div>
                            <div v-if="errors.address" class="text-red-500">
                                {{ errors.address }}
                            </div>
                        </div>
                        <!-- cl2end -->
                        </div>
                    </div>
                    <!-- Site Name -->
                </div>

                <button
                    type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none font-medium rounded-lg text-sm o px-5 py-2.5 text-center m-5 flex float-right"
                >
                    <img
                        class="w-5 h-5 mr-2"
                        src="../../../public/img/save.png"
                        alt=""
                    />Save
                </button>
            </form>
        </div>

        <!-- Public -->
        <div
            class="w-11/12 h-auto bg-elementBackground text-white tab-pannel-2 rounded-b-xl"
            id="tab-pannel-2"
            v-bind:class="{ hidden: openTab !== 2, block: openTab === 2 }"
        >
            <form
                class="mt-6"
                @submit.prevent="submit_public"
                enctype="multipart/form-data"
            >
                <div class="grid  mb-6 md:grid-cols-2">
                    <div class="ml-8 my-5">
                        <!-- LOGO -->
                        <div class="ml-10 my-5 sm:w-full sm:ml-4">
                            <label
                                for="logos"
                                class="block mb-2 text-md font-medium text-gray-900 dark:text-white"
                                >Logo</label
                            >
                            <label
                                for="logos"
                                class="flex flex-col items-center justify-center w-5/6 h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                            >
                                <div
                                    class="flex flex-col items-center justify-center pt-5 pb-6"
                                >
                                    <div class="absolute w-36">
                                        <img
                                            :src="pub_logoimageFile"
                                            alt=""
                                            class="items-center"
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
                                    @input="
                                        form_public.logos =
                                            $event.target.files[0]
                                    "
                                    @change="pub_logoshowImage($event)"
                                    accept="image/*"
                                    id="logos"
                                    type="file"
                                    class="hidden"
                                />
                            </label>
                            <div v-if="errors.logos" class="text-red-500">
                                {{ errors.logos }}
                            </div>
                        </div>
                        <!--Dark LOGO -->
                        <div class="ml-10 my-5 sm:w-full sm:ml-4">
                            <label
                                for="darklogo"
                                class="block mb-2 text-md font-medium text-gray-900 dark:text-white"
                                >Dark Logo</label
                            >
                            <label
                                for="darklogo"
                                class="flex flex-col items-center justify-center w-5/6 h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                            >
                                <div
                                    class="flex flex-col items-center justify-center pt-5 pb-6"
                                >
                                    <div class="absolute w-36">
                                        <img
                                            :src="pub_darklogoimageFile"
                                            alt=""
                                            class="items-center"
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
                                    @input="
                                        form_public.darklogo =
                                            $event.target.files[0]
                                    "
                                    @change="pub_darklogoshowImage($event)"
                                    accept="image/*"
                                    id="darklogo"
                                    type="file"
                                    class="hidden"
                                />
                            </label>
                            <!-- <div v-if="errors.darklogo" class="text-red-500">
                                {{ errors.darklogo }}
                            </div> -->
                        </div>
                        <!-- Fav Icon -->
                        <div class="ml-10 my-5 sm:w-full sm:ml-4">
                            <label
                                for="Favicons"
                                class="block mb-2 text-md font-medium text-gray-900 dark:text-white"
                                >Favicon</label
                            >
                            <label
                                for="Favicons"
                                class="flex flex-col items-center justify-center w-5/6 h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                            >
                                <div
                                    class="flex flex-col items-center justify-center pt-5 pb-6"
                                >
                                    <div class="absolute w-36">
                                        <img
                                            :src="pub_faviconimageFile"
                                            alt=""
                                            class="items-center"
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
                                    @input="
                                        form_public.favicons =
                                            $event.target.files[0]
                                    "
                                    @change="pub_faviconshowImage($event)"
                                    accept="image/*"
                                    id="Favicons"
                                    type="file"
                                    class="hidden"
                                />
                            </label>
                            <div v-if="errors.favicons" class="text-red-500">
                                {{ errors.favicons }}
                            </div>
                        </div>

                        <!-- col1end -->
                    </div>
                    <div class=" my-5">
                         <div
                        class="absolute sm:w-0 md:w-0.5 h-4/6 bg-white"
                    ></div>
                    <div class="pl-10">
                        <!-- Site Name -->
                        <div class="ml-10 my-5 sm:w-full sm:ml-4">
                            <label
                                for="sitenames"
                                class="block mb-2 text-md font-medium text-gray-900 dark:text-white"
                                >Site Name</label
                            >
                            <input
                                v-model="form_public.sitenames"
                                type="text"
                                id="sitenames"
                                :maxlength="psiteLength"
                                class="focus:ring-white focus:border-white border-white text-white text-sm rounded-xl block w-5/6 bg-elementBackground"
                                placeholder=""
                            />
                            <div
                                class="flex float-right pr-32 text-xs text-whiteTextColor"
                                v-text="
                                    psiteLength -
                                    form_public.sitenames.length +
                                    ' words left'
                                "
                            ></div>
                            <div v-if="errors.sitenames" class="text-red-500">
                                {{ errors.sitenames }}
                            </div>
                        </div>
                        <!-- Phone No -->
                        <div class="ml-10 my-5 sm:w-full sm:ml-4">
                            <label
                                for="phone_p"
                                class="block mb-2 text-md font-medium text-gray-900 dark:text-white"
                                >Phone No</label
                            >
                            <input
                                v-model="form_public.phone"
                                type="text"
                                id="phone_p"
                                :maxlength="phonelength"
                                class="focus:ring-white focus:border-white border-white text-white text-sm rounded-xl block w-5/6 bg-elementBackground"
                                placeholder=""
                            />
                            <div
                                class="flex float-right pr-32 text-xs text-whiteTextColor"
                                v-text="
                                    phonelength -
                                    form_public.phone.length +
                                    ' words left'
                                "
                            ></div>
                            <div v-if="errors.phone" class="text-red-500">
                                {{ errors.phone }}
                            </div>
                        </div>

                        <!-- Copy Right -->
                        <div class="ml-10 my-5 sm:w-full sm:ml-4">
                            <label
                                for="copyright_p"
                                class="block mb-2 text-md font-medium text-gray-900 dark:text-white"
                                >Copy Right</label
                            >
                            <input
                                v-model="form_public.copyright"
                                type="text"
                                id="copyright_p"
                                :maxlength="copyLength"
                                class="focus:ring-white focus:border-white border-white text-white text-sm rounded-xl block w-5/6 bg-elementBackground"
                                placeholder=""
                            />
                            <div
                                class="flex float-right pr-32 text-xs text-whiteTextColor"
                                v-text="
                                    copyLength -
                                    form_public.copyright.length +
                                    ' words left'
                                "
                            ></div>
                            <div v-if="errors.copyright" class="text-red-500">
                                {{ errors.copyright }}
                            </div>
                        </div>
                        <!-- FaceBook -->
                        <div class="ml-10 my-5 sm:w-full sm:ml-4">
                            <label
                                for="facebook"
                                class="block mb-2 text-md font-medium text-gray-900 dark:text-white"
                                >Facebook</label
                            >
                            <input
                                v-model="form_public.facebook"
                                type="text"
                                id="facebook_p"
                                :maxlength="pfacebookLength"
                                class="focus:ring-white focus:border-white border-white text-white text-sm rounded-xl block w-5/6 bg-elementBackground p-2"
                                placeholder=""
                            />
                            <div
                                class="flex float-right pr-32 text-xs text-whiteTextColor"
                                v-text="
                                    pfacebookLength -
                                    form_public.facebook.length +
                                    ' words left'
                                "
                            ></div>
                            <div v-if="errors.facebook" class="text-red-500">
                                {{ errors.facebook }}
                            </div>
                        </div>
                        <!-- YouTube1 -->
                        <div class="ml-10 my-5 sm:w-full sm:ml-4">
                            <label
                                for="youtube1"
                                class="block mb-2 text-md font-medium text-gray-900 dark:text-white"
                                >YouTube 1</label
                            >
                            <input
                                v-model="form_public.youtube1"
                                type="text"
                                id="youtube1_p"
                                :maxlength="pyoutube1Length"
                                class="focus:ring-white focus:border-white border-white text-white text-sm rounded-xl block w-5/6 bg-elementBackground p-2"
                                placeholder=""
                            />
                            <div
                                class="flex float-right pr-32 text-xs text-whiteTextColor"
                                v-text="
                                    pyoutube1Length -
                                    form_public.youtube1.length +
                                    ' words left'
                                "
                            ></div>
                            <div v-if="errors.youtube1" class="text-red-500">
                                {{ errors.youtube1 }}
                            </div>
                        </div>
                        <!-- Youtube2 -->
                        <div class="ml-10 my-5 sm:w-full sm:ml-4">
                            <label
                                for="youtube2_p"
                                class="block mb-2 text-md font-medium text-gray-900 dark:text-white"
                                >YouTube 2</label
                            >
                            <input
                                v-model="form_public.youtube2"
                                type="text"
                                id="youtube2_p"
                                :maxlength="pyoutube2Length"
                                class="focus:ring-white focus:border-white border-white text-white text-sm rounded-xl block w-5/6 bg-elementBackground p-2"
                                placeholder=""
                            />
                            <div
                                class="flex float-right pr-32 text-xs text-whiteTextColor"
                                v-text="
                                    pyoutube2Length -
                                    form_public.youtube2.length +
                                    ' words left'
                                "
                            ></div>
                            <div v-if="errors.youtube2" class="text-red-500">
                                {{ errors.youtube2 }}
                            </div>
                        </div>
                    </div>
                        <!-- col2end -->
                    </div>

            
                </div>

                <button
                    type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center m-5 flex float-right"
                >
                    <img
                        class="w-5 h-5 mr-2"
                        src="../../../public/img/save.png"
                        alt=""
                    />Save
                </button>
            </form>
        </div>
    </div>
</template>
