<script setup>
import NavBar from "../../Components/NavBar.vue";
import Header from "../../Components/Header.vue";
import Pagination from "../../Components/Pagination.vue";
import { Inertia } from "@inertiajs/inertia";
import { ref, provide } from "vue";
import { useForm, Head } from "@inertiajs/inertia-vue3";
import { Link } from "@inertiajs/inertia-vue3";

const props = defineProps({
    exams: Object,
});

window.history.forward();

let showMenu = ref(true);
provide('showMenu', showMenu);
</script>

<template>
    <Head title="Exam" />
    <NavBar active="10"> </NavBar>
    <Header headername="Exam List" class="w-full pr-36 transition-all duration-500" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"/>
    <div
        class="absolute h-auto w-full headercustomleft top-32 bg-elementBackground rounded-lg mt-5 ml-5 px-4 py-4 transition-all duration-500 pr-36" :class="showMenu ? 'ml-64 pl-4 pr-64' : 'ml-28'"
    >
        <table class="text-white w-full rounded-lg">
            <tr class="opacity-70 customfontsize">
                <th class="py-4">EXAM NAME</th>
                <th class="py-4">CLASS NAME</th>
                <th class="py-4">FULL MARK</th>
                <th class="py-4">FAIL MARK</th>
                <th class="py-4">DATE</th>
                <th class="py-4">ENTRY</th>
                <th class="py-4">SETTINGS</th>
            </tr>
            <tbody class="text-sm mt-3">
                <tr class="border-b" v-for="exam in exams" :key="exam.id">
                    <td class="text-center py-4">{{ exam.e_name }}</td>
                    <td class="text-center py-4">
                        {{ exam.c_name }}
                    </td>
                    <td class="text-center py-4 text-green-500">
                        {{ exam.full_mark }}
                    </td>
                    <td class="text-center py-4 text-tertiaryBackground">
                        {{ exam.fail_mark }}
                    </td>
                    <td class="text-center py-4">
                        {{ exam.e_duedate }}
                    </td>
                    <td
                        class="text-center py-4 text-yellowTextColor font-bold underline"
                    >
                        <Link :href="route('exam.entrymark', exam.id)" :id="exam.id">
                            Entry</Link
                        >
                    </td>
                    <td class="text-center py-4 text-yellowTextColor underline">
                        <Link :href="route('exam.edit', exam.id)" :id="exam.id"
                            >Edit</Link
                        >
                    </td>
                </tr>
            </tbody>
        </table>
        <Link :href="route('exam.create')">
            <button
                class="flex item-center py-2 px-5 text-whiteTextColor text-md bg-blueTextColor rounded-xl float-right mt-5"
                type="submit"
            >
                <img
                    src="../../../../public/img/addlogo.png"
                    alt=""
                    class="w-5 h-5 pt-0.5 mr-1"
                />

                <span class="font-semibold">Add Exam</span>
            </button>
        </Link>
    </div>
</template>
