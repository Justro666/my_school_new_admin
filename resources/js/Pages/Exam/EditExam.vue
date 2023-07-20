<script setup>
import NavBar from "../../Components/NavBar.vue";
import Header from "../../Components/Header.vue";
import Pagination from "../../Components/Pagination.vue";
import { Inertia } from "@inertiajs/inertia";
import { ref } from "vue";
import { useForm, Head } from "@inertiajs/inertia-vue3";
import { Link } from "@inertiajs/inertia-vue3";

const props = defineProps({
    classes: Object,
    errors: Object,
    exam: Object
});

let markError = ref(false);



const form = useForm({
    examName: props.exam.e_name,
    class: props.exam.class_id,
    date: props.exam.e_duedate,
    fullmark: props.exam.full_mark,
    failmark: props.exam.fail_mark,
    _method : "put"
});

const submit = () => {
    if (form.failmark >= form.fullmark) {
        markError.value = true;
    } else {
        markError.value = false;
        Inertia.post(route("exam.update",props.exam.id), form, {
            onSuccess: (data) => {

            },
        });
    }
};
</script>

<template>
    <Head title="Edit Exam" />
    <NavBar active="10"> </NavBar>
    <Header headername="Edit Exam" />
    <div
        class="absolute h-auto w-4/5 headercustomleft top-32 bg-elementBackground rounded-lg mt-5 ml-5 px-5 py-8"
    >
        <form @submit.prevent="submit">
            <div class="flex flex-col items-center justify-center">
                <div class="flex w-96 flex-col items-center">
                    <span class="text-white font-semibold mr-auto my-2"
                        >Exam Name
                    </span>
                    <input
                        type="text"
                        v-model="form.examName"
                        class="w-full mt-2 h-9 rounded-xl bg-secondaryBackground text-whiteTextColor border-whiteTextColor focus:ring-white focus:border-white"
                    />
                    <div class="text-tertiaryBackground mr-auto m-1 h-5">
                        <p v-if="errors.examName">{{ errors.examName }}</p>
                    </div>
                </div>
                <div class="flex flex-row my-1">
                    <div class="flex flex-col items-center mr-8">
                        <span class="text-white font-semibold mr-auto my-2"
                            >Class
                        </span>
                        <select
                            class="w-44 h-9 rounded-xl bg-secondaryBackground text-whiteTextColor border-whiteTextColor focus:outline-0 focus:ring-whiteTextColor focus:border-whiteTextColor"
                            @change="change"
                            v-model="form.class"
                        >
                            <option
                                :value="classdata.id"
                                v-for="classdata in classes"
                                :key="classdata.id"
                            >
                                {{ classdata.c_name }}
                            </option>
                        </select>
                        <div class="text-tertiaryBackground mr-auto h-5">
                            <p v-if="errors.class">{{ errors.class }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="text-white font-semibold mr-auto my-2"
                            >Date
                        </span>
                        <input
                            type="date"
                            v-model="form.date"
                            @input="change"
                            class="w-44 h-9 rounded-xl bg-secondaryBackground text-whiteTextColor border-whiteTextColor focus:outline-0 focus:ring-whiteTextColor focus:border-whiteTextColor"
                        />
                        <div class="text-tertiaryBackground mr-auto h-5">
                            <p v-if="errors.date">{{ errors.date }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-row my-1">
                    <div class="flex flex-col items-center mr-8">
                        <span class="font-semibold mr-auto my-2 text-green-500"
                            >Full Mark
                        </span>
                        <input
                            type="number"
                            v-model="form.fullmark"
                            class="w-44 mt-2 h-9 rounded-xl bg-secondaryBackground text-whiteTextColor border-whiteTextColor focus:ring-white focus:border-white"
                        />
                        <div class="text-tertiaryBackground mr-auto h-5">
                            <p v-if="errors.fullmark">{{ errors.fullmark }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-center">
                        <span
                            class="font-semibold mr-auto my-2 text-tertiaryBackground"
                            >Fail Mark
                        </span>
                        <input
                            type="number"
                            v-model="form.failmark"
                            class="w-44 mt-2 h-9 rounded-xl bg-secondaryBackground text-whiteTextColor border-whiteTextColor focus:ring-white focus:border-white"
                        />
                        <div class="text-tertiaryBackground mr-auto h-10">
                            <p v-if="errors.failmark">{{ errors.failmark }}</p>
                        </div>
                        <div class="text-tertiaryBackground mr-auto h-10">
                            <p v-show="markError">Fail Mark is not Valid.</p>
                        </div>
                    </div>
                </div>
            </div>

            <button
                class="flex item-center py-2 px-5 text-whiteTextColor text-md bg-blueTextColor rounded-xl float-right mt-5"
                type="submit"
            >
                <img
                    src="../../../../public/img/bx_save.png"
                    alt=""
                    class="w-5 h-5 pt-0.5 mr-1"
                />

                <span class="font-semibold">Save</span>
            </button>
        </form>
    </div>
</template>

<style scoped>
input {
    color-scheme: dark;
}
</style>
