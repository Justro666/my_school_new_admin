<script setup>
import NavBar from "../Components/NavBar.vue";
import Header from "../Components/Header.vue";
import Pagination from "../Components/Pagination.vue";
import { Link, Head, usePage, useForm } from "@inertiajs/inertia-vue3";
import { provide, ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/css/index.css";

let props = defineProps({
    errors: Object,
    contactdetail: Object,
});

// console.log(props.contactdetail);
const form = useForm({
    username: props.contactdetail.username,
    email: props.contactdetail.email,
    message: props.contactdetail.message,
    replymessage: "",
});

let isloading = ref(false);
let loader = ref("bars");
let color = ref("#000");
let width = ref("100");
let height = ref("200");
let fullPage = ref(true);

const submit = () => {
    Inertia.patch(route("contact.update", props.contactdetail.id), form, {
        onError: (data) => {
            isloading.value = false;
        },
        onStart: (data) => {
            isloading.value = true;
        },
        onSuccess: () => {
            setTimeout(() => {
                Inertia.get(route("contact.index"));
            }, 2000);

            // isloading.value = false;
        },
    });
};



let showMenu = ref(true);
provide("showMenu", showMenu);

window.history.forward();
</script>

<template>
    <!-------------------- Navbar&header -------------------->

    <NavBar />
    <Head title="Messages"> </Head>
    <Header
        headername="Messages"
        class="w-full pr-36 transition-all duration-500"
        :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"
    />

    <loading
        v-model:active="isloading"
        :is-full-page="fullPage"
        :loader="loader"
        :color="color"
        :width="width"
        :height="height"
    />

    <div
        class="absolute h-auto w-full headercustomleft top-32 customblack transition-all duration-500 pr-36"
        :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"
    >
        <div
            v-if="$page.props.flash.worng"
            class="bg-red-600 text-white w-full items-center flex justify-center h-10 text-lg rounded-md mb-5"
        >
            <span
                ><img
                    src="../../../public/img/ri_mail-close-line.png"
                    alt=""
                    class="w-7 h-7 pt-0.5"
            /></span>
            <p class="mx-5">{{ $page.props.flash.worng }}</p>
        </div>
        <div
            v-if="$page.props.flash.mailSent"
            class="bg-green-600 text-white w-full items-center flex justify-center h-10 text-lg rounded-md mb-5"
        >
            <span
                ><img
                    src="../../../public/img/ri_mail-check-line.png"
                    alt=""
                    class="w-7 h-7 pt-0.5"
            /></span>
            <p class="mx-5">{{ $page.props.flash.mailSent }}</p>
        </div>

        <div
            class="px-10 my-6 w-full bg-elementBackground mr-10 rounded-xl flex flex-col items-center"
        >
            <div class="w-4/6 pb-6 text-white">
                <div class="py-4">
                    <h1 class="text-xl font-bold pb-3">Email</h1>
                    <p class="pl-4">{{ contactdetail.email }}</p>
                </div>
                <div class="py-4">
                    <h1 class="text-xl font-bold pb-3">Message</h1>
                    <p class="pl-4">{{ contactdetail.message }}</p>
                </div>
            </div>

            <div class="w-4/6 text-white">
                <form @submit.prevent="submit" class="flex flex-col items-end">
                    <label for="Reply" v-if="contactdetail.reply" class="w-full text-xl font-bold pb-3">Your Reply is</label>
                    <label for="Reply" v-else class="w-full text-xl font-bold pb-3"
                        >Reply</label
                    >
                    <p v-if="contactdetail.reply" class="pl-4 pb-5 w-full">{{contactdetail.reply}}</p>
                    <textarea
                        v-else
                        name=""
                        id="Reply"
                        v-model="form.replymessage"
                        class="h-48 w-full resize-none rounded-xl bg-secondaryBackground focus:outline-none focus:ring-white focus:border-white border-white"
                    ></textarea>
                    <div
                        v-if="errors.replymessage"
                        class="text-red-700 text-md w-full"
                    >
                        {{ errors.replymessage }}
                    </div>

                    <div class="py-3" v-if="!contactdetail.reply">
                        <button
                            class="py-2 px-5 text-whiteTextColor text-md bg-blueTextColor rounded-xl flex items-center"
                        >
                            <img
                                src="../../../public/img/send.png"
                                alt=""
                                class="w-5 h-5 pt-0.5"
                            />
                            <span class="mx-2">Send</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
        <div class="w-4/6 text-white justify-start mt-5 ml-5 mb-10">
                <button>
                    <Link
                        :href="route('contact.index')"
                        class="underline underline-offset-4 hidden md:block"
                        >back
                    </Link>
                </button>
        </div>
    </div>

</template>

<style></style>
