<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import NavBar from "../Components/NavBar.vue";
import Header from "../Components/Header.vue";
import Toolsbar from "../Components/Toolsbar.vue";
import NotiError from "../Components/NotiError.vue";
import { Inertia } from "@inertiajs/inertia";
import { ref, provide } from "vue";
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/css/index.css';

const props = defineProps({
    errors: {
        type: Object,
    },
    students: {
        type: Object,
    },
    classes: {
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
    student: props.students[0].studentId,
    class: props.classes[0].classId,
    choice: null,
    category: 1,
    title: '',
    description: '',
});

let stuClassErr = ref("");
let showError = ref(false);

const submit = () => {
    if (
        form.choice == null
    ) {
        stuClassErr.value = "You need to choose where you want to send your mail.";
    } else {
        Inertia.post(route("mailtool.store"), form, {
            onError: (errors) => {

            },
            onSuccess: (data) => {

            },
            onStart: (data) => {
                isloading.value = true;
            },
            onFinish: () => {
                isloading.value = false;

                if (props.errors.occurerror) {
                    showError.value = true;

                    setTimeout(() => {
                        showError.value = false;
                    }, 2000);
                }
            }
        });
    }
};

function studentOn() {
    document.getElementById("classroom").style.display = "none";
    document.getElementById("student").style.display = "block";

    document.getElementById("classroomBox").style.display = "block";
    document.getElementById("studentBox").style.display = "none";

    form.choice = true;
}


function classOn() {
    document.getElementById("student").style.display = "none";
    document.getElementById("classroom").style.display = "block";

    document.getElementById("studentBox").style.display = "block";
    document.getElementById("classroomBox").style.display = "none";

    form.choice = false;
}

function information() {
    form.category = 1;
}

function directMessage() {
    form.category = 2;
}

function alert() {
    form.category = 3;
}

const showMenu = ref(true);

provide('showMenu', showMenu);

let titleLength = ref(props.characterLength[0].CHARACTER_MAXIMUM_LENGTH);
let descriptionLength = ref(props.characterLength[1].CHARACTER_MAXIMUM_LENGTH);

window.history.forward();
</script>

<template>

    <Head title="Send Mail" />

    <NavBar @mouseover="showMenu = true" @mouseout="showMenu = false"> </NavBar>
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

        <Toolsbar active="1" />
        <form @submit.prevent="submit">
            <div class="w-full h-full py-8 bg-secondaryBackground rounded-b-xl flex flex-col items-center">
                <div class="flex flex-col md:flex-row space-x-20">
                    <div class="flex flex-col space-y-10">
                        <div class="flex h-36 space-x-10">
                            <div class="flex flex-col items-center justify-around">
                                <div class="flex justify-start w-full">
                                    <label for="" class="pl-10 pt-3 text-whiteTextColor my-3">Students</label>
                                </div>

                                <div class="flex items-center space-x-10">
                                    <select id="student" v-model="form.student" style="display: none"
                                        class="w-72 ml-10 rounded-xl bg-secondaryBackground text-whiteTextColor border-whiteTextColor focus:outline-0 focus:ring-whiteTextColor focus:border-whiteTextColor">
                                        <option :value="student.studentId" v-for="student in students" :key="student">
                                            {{ student.studentName }}
                                        </option>
                                    </select>

                                    <select name="" id="studentBox" disabled
                                        class="w-72 rounded-xl bg-secondaryBackground text-whiteTextColor border-whiteTextColor focus:outline-0">
                                        <option></option>
                                    </select>

                                    <input type="radio" value="check" name="check" @click="studentOn"
                                        class="cursor-pointer" />
                                </div>

                                <div class="flex justify-start w-full">
                                    <label for="" class="pl-10 text-whiteTextColor my-3">Classes</label>
                                </div>

                                <div class="flex items-center space-x-10">
                                    <select id="classroom" v-model="form.class" style="display: none"
                                        class="w-72 ml-10 rounded-xl bg-secondaryBackground text-whiteTextColor border-whiteTextColor focus:outline-0 focus:ring-whiteTextColor focus:border-whiteTextColor">
                                        <option :value="clas.classId" v-for="clas in classes" :key="clas">
                                            {{ clas.className }}
                                        </option>
                                    </select>

                                    <select name="" id="classroomBox" disabled
                                        class="w-72 rounded-xl bg-secondaryBackground text-whiteTextColor border-whiteTextColor focus:outline-0">
                                        <option></option>
                                    </select>

                                    <input type="radio" value="check" name="check" @click="classOn"
                                        class="cursor-pointer" />
                                </div>
                            </div>
                        </div>
                        <label for="" class="text-red-700">{{ stuClassErr }}</label>
                        <div class="flex my-16">
                            <div class="flex flex-col items-center mx-8">
                                <input type="radio" class="cursor-pointer" name="category" v-model="form.information"
                                    @click="information" :checked="true" />
                                <label for="" class="mt-3 text-blueTextColor">Information</label>
                            </div>
                            <div class="flex flex-col items-center mx-8">
                                <input type="radio" class="cursor-pointer" name="category" v-model="form.directMessage"
                                    @click="directMessage" :checked="false" />
                                <label for="" class="mt-3 text-yellowTextColor">Direct Message</label>
                            </div>
                            <div class="flex flex-col items-center mx-8">
                                <input type="radio" class="cursor-pointer" name="category" v-model="form.alert"
                                    @click="alert" :checked="false" />
                                <label for="" class="mt-3 text-tertiaryBackground">Alert</label>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col space-y-3">
                        <div class="flex flex-col">
                            <label for="" class="text-whiteTextColor">Title</label>
                            <input type="text" v-model="form.title"
                                class="w-96 my-2 rounded-xl bg-secondaryBackground text-whiteTextColor border-whiteTextColor focus:ring-white focus:border-white"
                                :maxlength="titleLength" />
                            <div class="flex justify-end text-xs text-whiteTextColor"
                                v-text="(titleLength - form.title.length + ' words left')"></div>
                            <div v-if="errors.title" class="text-red-500 font-bold text-md">
                                {{ errors.title }}
                            </div>
                        </div>

                        <div class="flex flex-col">
                            <label for="" class="text-whiteTextColor">Description</label>
                            <textarea v-model="form.description"
                                class="w-96 h-32 my-2 rounded-xl bg-secondaryBackground text-whiteTextColor border-whiteTextColor focus:ring-white focus:border-white resize-none scrollYStyle"
                                :maxlength="descriptionLength"></textarea>
                            <div class="flex justify-end text-xs text-whiteTextColor"
                                v-text="(descriptionLength - form.description.length + ' words left')"></div>
                            <div v-if="errors.description" class="text-red-500 font-bold text-md">
                                {{ errors.description }}
                            </div>
                        </div>

                    </div>
                </div>
                <div class="flex w-5/6 mt-3 flex-row-reverse">
                    <button type="submit" class="py-2 px-8 bg-blueTextColor text-whiteTextColor rounded-xl flex">
                        <img src="../../../public/img/send.png" alt="" class="w-5 h-5 pt-0.5 mr-3">
                        Send
                    </button>
                </div>
            </div>
        </form>

        <div class="py-5">
            <button>
                <a href="/mailtool" class="underline underline-offset-4 hidden md:block text-whiteTextColor">back</a>
            </button>
        </div>
    </div>
</template>
