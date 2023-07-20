<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";
import NavBar from "../Components/NavBar.vue";
import Header from "../Components/Header.vue";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});

window.history.forward();
</script>

<template>
    <!-------------------- Navbar&header -------------------->
    <NavBar active="1"> </NavBar>
    <Header headername="Student" />
    <!---------------- body ----------------------->
    <div
        class="absolute flex flex-col h-full w-5/6 headercustomleft top-32 customblack"
    >
        <!--------------- 4 Student Coubt Box ---------------->
        <div
            class="grid md:grid-cols-4 grid-cols-2 md:place-content-evenly w-12/12 place-items-center"
        >
            <StudentCountBox
                studentcount="140"
                studenttype="All Students"
                class="mt-2 order-1"
            />
            <StudentCountBox
                studentcount="65"
                studenttype="Japanese"
                class="mt-2 order-2"
            />
            <StudentCountBox
                studentcount="45"
                studenttype="Web Development"
                class="mt-2 order-3"
            />
            <StudentCountBox
                studentcount="30"
                studenttype="JAVA SE"
                class="mt-2 order-4"
            />
        </div>

        <div class="sm:ml-12 ml-5 mt-7 flex md:flex-row flex-col">
            <!------------------- Active class table --------------------------------->
            <ActiveClassTable />
            <!--------------------- Recent Action ----------------------------->
            <RecentAction class="sm:ml-12 mx-auto" />
        </div>

        <!-- Online student -->
        <h3 class="text-white ml-20 mt-5">Online Students</h3>
        <div
            class="grid lg:grid-cols-4 grid-cols-2 lg:place-content-evenly w-12/12 place-items-center mt-3.5 mb-3.5 customalign"
        >
            <OnlineStudentCard
                studentname="Thazin Aung"
                classname="SEJ"
                address="Taung Ngu,Bago"
                actcondition="Active"
            />
            <OnlineStudentCard
                studentname="Thazin Aung"
                classname="SEJ"
                address="Taung Ngu,Bago"
                actcondition="3 min ago"
                class="lg:ml-0 ml-3"
            />
            <OnlineStudentCard
                studentname="Thazin Aung"
                classname="SEJ"
                address="Taung Ngu,Bago"
                actcondition="Active"
                class="lg:mt-0 mt-3"
            />
            <OnlineStudentCard
                studentname="Thazin Aung"
                classname="SEJ"
                address="Taung Ngu,Bago"
                actcondition="1 hour ago"
                class="lg:mt-0 mt-3 lg:ml-0 ml-3"
            />
        </div>






























    </div>
</template>
<style scoped>
.customalign {
    margin-bottom: 1.3em;
}
</style>
