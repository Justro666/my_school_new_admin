<script setup>
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Inertia } from "@inertiajs/inertia";
import{ ref } from "vue";
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/css/index.css';

const loginform = useForm({
    _method : "POST",
    email: "",
    password: "",
});

const props = defineProps({
     errors: {
        type: Object,
    },
    categories: {
        type: Object,
        }

})
//loading Bars
let isloading =ref(false);
let loader = ref('bars');
let color = ref('#3265EE');
let width = ref('100');
let height = ref('200');
let fullPage = ref(true);

const submit = ()=>{
Inertia.post(route("login.store", loginform), {
        onError: (data) => {

            // inputField.value.focus();
        },
        onStart:(data)=>{
            isloading.value = true ;
        },
        onFinish:()=>{
            isloading.value = false ;
        }
        
    });
}
</script>

<template>
 <loading v-model:active="isloading" :is-full-page="fullPage" :loader="loader" :color="color" :width="width" :height="height" />
    <Head title="Log in">
        <link rel="icon" :href="$page.props.setting.favicon" />
    </Head>

    <AuthenticationCard>
             <div v-if="$page.props.flash.verify" class="text-red-500 mt-2">
                            {{ $page.props.flash.verify }}
                        </div>
                        <div v-if="$page.props.flash.banned" class="text-red-500 mt-2">
                            {{ $page.props.flash.banned }}
                        </div>
        <div class="mb-4 font-medium text-sm text-green-600"></div>

        <form @submit.prevent="submit">
            <div class="flex-col justify-center items-center">
                <!-- <InputLabel for="email" value="Email" /> -->
                <TextInput
                    v-model="loginform.email"
                    type="email"
                    class="shadow-md rounded-lg mt-4 block md:w-64 w-80 ring-1 ring-white focus:ring-gray-300"
                    placeholder="Email"
                    required
                    autofocus
                />
                <div v-if="errors.email" class="text-red-500 mt-2">
                            {{ errors.email }}
                        </div>

                <div class="mt-4">
                    <!-- <InputLabel for="password" value="Password" /> -->
                    <TextInput
                        v-model="loginform.password"
                        id="password"
                        type="password"
                        class="mt-5 block shadow-md rounded-lg md:w-64 w-80 ring-1 ring-white focus:ring-gray-300"
                        placeholder="Password"
                        required
                        autocomplete="current-password"
                    />
                    <div v-if="$page.props.flash.pmessage" class="text-red-500 mt-2">
                            {{ $page.props.flash.pmessage }}
                        </div>
                </div>
            </div>

            <div class="block mt-5">
                <PrimaryButton
                    class="w-80 md:w-64 bg-whiteTextColor md:text-whiteTextColor md:bg-blue-700 rounded-lg shadow-lg text-primaryBackground text-center"
                >
                    Login
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>

<style></style>
