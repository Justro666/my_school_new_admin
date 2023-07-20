<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";
import NavBar from "../Components/NavBar.vue";
import Header from "../Components/Header.vue";
import Pagination from "../Components/Pagination.vue";
import moment from "moment";
import { ref, provide } from "vue";

const props = defineProps({
    recentActions: {
        type: Object,
    },
});

let value = props.recentActions.data[0].auditable_type;



window.history.forward();

let showMenu = ref(true);
provide('showMenu', showMenu);
</script>

<template>
    <Head title="Recent Actions" />

    <NavBar active=1> </NavBar>
    <Header headername="Recent Action" class="w-full pr-36 transition-all duration-500" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"/>

    <div class="absolute h-auto w-full overflow-x-hidden py-10 px-5 headercustomleft top-32 customblack transition-all duration-500 pr-36" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'">
        <div class="w-full p-3 relative bg-secondaryBackground rounded-xl flex flex-col items-center">
            <div class="p-4 w-full rounded-xl">
                <div v-for="recentAction in props.recentActions.data" :key="recentAction"
                    class="text-whiteTextColor p-3">
                    <!-- {{
                        recentAction.name + " " + recentAction.event + ' ' + recentAction.url.split('/')[3] + ' of ' +
                            recentAction.auditable_type.replace(/s\\/, '/').split('/')[1] + " " + moment(recentAction.updated_at).calendar()
                    }} -->

                    <span class="text-yellowTextColor">{{ recentAction.name }}</span>
                    <span>{{ " " + recentAction.event + " "}}</span>
                    <span class="text-yellowTextColor">{{ recentAction.url.split('/')[3] }}</span>
                    <span>{{ " of " }}</span>
                    <span class="text-yellowTextColor">{{ recentAction.auditable_type.replace(/s\\/, '/').split('/')[1] }}</span>
                    <span>{{ " at " }}</span>
                    <span>{{ moment(recentAction.updated_at).calendar() }}</span>
                    <hr class="mt-2">
                </div>

                <div class="flex justify-center items-center my-10">
                    <Pagination :links="recentActions.links"> </Pagination>
                </div>
            </div>
        </div>

        <div class="fixed left-42 bottom-5">
            <button>
                <a href="/dashboard" class="underline underline-offset-4 hidden md:block text-whiteTextColor">back</a>
            </button>
        </div>
    </div>
</template>
