<script setup>
import NavBar from "../../Components/NavBar.vue";
import Header from "../../Components/Header.vue";
import Pagination from "../../Components/Pagination.vue";
import { Inertia } from "@inertiajs/inertia";
import { ref, provide } from "vue";
import { useForm, Head } from "@inertiajs/inertia-vue3";
import { Link } from "@inertiajs/inertia-vue3";

const props = defineProps({
    className: Object,
    exams: Object,
    students: Array,
    selectedExam: Object,
    summary: Object,
    student_exams: Object,
    errors: Object,
});

const form = useForm({
    class: null,
    exam: props.selectedExam.id,
    students: props.students,
    marks: props.student_exams.map((item) => item.mark),
    showSave: props.students.length > 0 ? true : false,
});

const change = () => {
    Inertia.get(route("exam.entrymark", form.exam), {
        onSuccess: (data) => {},
    });
};

const submit = () => {
    Inertia.post(route("exam.saveMark"), form, {
        onSuccess: (data) => {},
    });
};

let showMenu = ref(true);
provide('showMenu', showMenu);

window.history.forward();
</script>

<template>
    <Head title="Entry Mark" />
    <NavBar active="10"> </NavBar>
    <Header headername="Entry Mark" class="w-full pr-36 transition-all duration-500" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"/>
    <div
        class="absolute h-auto w-full headercustomleft top-32 bg-elementBackground rounded-lg mt-5 ml-5 px-10 py-8 transition-all duration-500 pr-36" :class="showMenu ? 'ml-64 pl-4 pr-64' : 'ml-28'"
    >
        <form @submit.prevent="submit">
            <div class="flex justify-start items-center">
                <div class="w-1/4 flex items-center">
                    <span class="text-white font-semibold"
                        >Class:
                        <span class="text-yellowTextColor mx-5">{{
                            className
                        }}</span>
                    </span>
                </div>
                <div class="w-1/4 flex items-center">
                    <span class="text-white font-semibold">Exam: </span>
                    <select
                        class="mx-5 w-60 h-10 rounded-xl bg-secondaryBackground text-whiteTextColor border-whiteTextColor focus:outline-0 focus:ring-whiteTextColor focus:border-whiteTextColor"
                        @change="change"
                        v-model="form.exam"
                    >
                        <option
                            :value="exam.id"
                            v-for="exam in exams"
                            :key="exam.id"
                        >
                            {{ exam.e_name }}
                        </option>
                    </select>
                </div>
                <div class="w-1/4 flex items-center">
                    <span class="text-green-500 font-semibold"
                        >Full Mark: {{ selectedExam.full_mark }}</span
                    >
                </div>
                <div class="w-1/4 flex items-center">
                    <span class="text-tertiaryBackground font-semibold"
                        >Fail Mark: {{ selectedExam.fail_mark }}
                    </span>
                </div>
            </div>

            <div class="flex mt-10 items-center">
                <div class="w-1/2">
                    <div
                        class="flex items-center my-6"
                        v-for="(student, index) in form.students"
                        :key="student.id"
                    >
                        <p class="text-white flex-auto">{{ student.name }} :</p>
                        <select
                            class="w-60 h-10 rounded-xl bg-secondaryBackground text-whiteTextColor border-whiteTextColor focus:outline-0 focus:ring-whiteTextColor focus:border-whiteTextColor"
                            v-model="form.marks[index]"
                        >
                            <option
                                :value="mark"
                                v-for="mark in selectedExam.full_mark"
                                :key="mark"
                            >
                                {{ mark }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="w-1/2">
                    <table
                        class="text-white w-96 mx-auto bg-secondaryBackground drop-shadow-lg rounded-lg"
                        v-show="summary.length > 0"
                    >
                        <thead>
                            <tr>
                                <th class="text-yellowTextColor p-2">NAME</th>
                                <th class="text-yellowTextColor p-2">
                                    PERCENTAGE
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <tr
                                class="border-b-2 border-b-white border-opacity-10"
                                v-for="student in summary"
                                :key="student"
                            >
                                <td class="p-2">{{ student.name }}</td>
                                <td
                                    class="p-2"
                                    :class="{
                                        'text-green-500':
                                            Number(student.percentage) == 100,
                                        'text-yellowTextColor':
                                            Number(student.percentage) <= 99 &&
                                            Number(student.percentage) >= 75,
                                        'text-tertiaryBackground':
                                            Number(student.percentage) < 75,
                                    }"
                                >
                                    {{ Number(student.percentage).toFixed(1) }}%
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="text-tertiaryBackground mr-auto h-5">
                <p v-if="errors.marks">{{ errors.marks }}</p>
            </div>
            <button
                class="flex item-center py-2 px-5 text-whiteTextColor text-md bg-blueTextColor rounded-xl float-right mt-5"
                type="submit"
                v-show="form.showSave"
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
