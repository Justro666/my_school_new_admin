<script setup>
import NavBar from "../../Components/NavBar.vue";
import Header from "../../Components/Header.vue";
import Pagination from "../../Components/Pagination.vue";
import { Inertia } from "@inertiajs/inertia";
import { ref, provide } from "vue";
import { useForm, Head } from "@inertiajs/inertia-vue3";
import { Link } from "@inertiajs/inertia-vue3";

const props = defineProps({
    classes: Object,
    students: Object,
    attendance: Object,
});

let summary = ref([]);

const form = useForm({
    class: null,
    date: new Date().toISOString().substr(0, 10),
    students: [],
    attendance: [],
    showSave: false,
});

const change = () => {
    form.attendance = [];
    Inertia.post(
        route("attendance.getstudent"),
        { id: form.class, date: form.date },
        {
            onSuccess: (data) => {
                form.students = data.props.students;
                summary.value = data.props.summary;
                for (let index = 0; index < form.students.length; index++) {
                    form.showSave = true;
                    if (data.props.attendance.length > 0)
                        form.attendance.push(
                            data.props.attendance[index].attendance
                        );
                    else form.attendance.push(1);
                }
            },
        }
    );
};

const submit = () => {
    Inertia.post(route("attendance.create"), form, {
        onSuccess: (data) => {
            summary.value = data.props.summary;
        },
    });
};

window.history.forward();

let showMenu = ref(true);
provide('showMenu', showMenu);
</script>

<template>
    <Head title="Attendance" />
    <NavBar active="9"> </NavBar>
    <Header headername="Attendance" class="w-full pr-36 transition-all duration-500" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"/>
    <div
        class="absolute h-auto w-5/6 headercustomleft top-32 bg-elementBackground rounded-lg p-8 transition-all duration-500 mr-36" :class="showMenu ? 'ml-64 pl-4 pr-64' : 'ml-28'"
    >
        <form @submit.prevent="submit">
            <div class="flex justify-start items-center">
                <div class="w-1/3 flex items-center">
                    <span class="text-white font-semibold">Class: </span>
                    <select
                        class="mx-5 w-60 h-9 rounded-xl bg-secondaryBackground text-whiteTextColor border-whiteTextColor focus:outline-0 focus:ring-whiteTextColor focus:border-whiteTextColor"
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
                </div>
                <div class="w-1/3 flex items-center">
                    <span class="text-white font-semibold">Date: </span>
                    <input
                        type="date"
                        v-model="form.date"
                        @input="change"
                        class="mx-5 w-44 h-9 rounded-xl bg-secondaryBackground text-whiteTextColor border-whiteTextColor focus:outline-0 focus:ring-whiteTextColor focus:border-whiteTextColor"
                    />
                </div>
            </div>

            <div class="flex mt-10">
                <div class="w-1/2">
                    <div
                        class="flex items-center my-6"
                        v-for="(student, index) in form.students"
                        :key="student.id"
                    >
                        <p class="text-white flex-auto">{{ student.name }} :</p>
                        <input
                            type="radio"
                            :name="`attendance${student.id}`"
                            v-model="form.attendance[index]"
                            value="1"
                            id="attend"
                            class="cursor-pointer"
                            checked
                        />
                        <label for="attend" class="text-white mr-5 ml-2"
                            >Attend</label
                        >
                        <input
                            type="radio"
                            :name="`attendance${student.id}`"
                            v-model="form.attendance[index]"
                            value="0.5"
                            id="late"
                            class="cursor-pointer"
                        />
                        <label for="late" class="text-white mr-5 ml-2"
                            >Late</label
                        >
                        <input
                            type="radio"
                            :name="`attendance${student.id}`"
                            v-model="form.attendance[index]"
                            id="absent"
                            value="0"
                            class="cursor-pointer"
                        />
                        <label for="absent" class="text-white mr-5 ml-2"
                            >Absent</label
                        >
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
                                    {{ student.percentage.toFixed(1) }}%
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <button
                class="flex py-2 px-5 text-whiteTextColor text-md bg-blueTextColor rounded-xl mt-5"
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
