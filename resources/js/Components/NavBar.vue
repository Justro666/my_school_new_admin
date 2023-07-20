<script setup>
import { computed, ref, inject } from "vue";
import { Link, Head, usePage } from "@inertiajs/inertia-vue3";

const props = defineProps({
    href: String,
    active: {
        props: Number,
        default: 0,
    },
});

let showMenu = inject("showMenu");
let limit = ref(0);

localStorage.getItem('pageLimitFour') == "true" ? limit.value = 4 : limit.value = 8;

// const classes = computed(() => {
//     return props.active
//         ? "inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition"
//         : "inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition";
// });

const setLimit = () => {
    console.log(typeof Boolean(localStorage.getItem('pageLimitFour')));
    localStorage.setItem('pageLimitFour', (localStorage.getItem('pageLimitFour')=="true" ? "false" : "true"));
}
</script>

<template>
    <Head>
        <link rel="icon" :href="$page.props.setting.favicon" />
    </Head>
    <!------------- Nav ---------->
    <div
        class="customnavcolor min-h-full fixed z-10 transition-all duration-500"
        :class="showMenu ? 'w-60' : 'w-24'"
        @mouseover="showMenu = true"
        @mouseout="showMenu = false"
    >
        <!-- Menu -->
        <div class="py-3 h-screen">
            <!-------- Logo ---------->
            <div class="pt-5 w-full text-center h-32">
                <img
                    :src="$page.props.setting.logo"
                    alt=""
                    class="sm:w-12 md:w-16 mx-auto"
                />
                <p class="text-white font-semibold pt-2">
                    {{ $page.props.setting.sitename }}
                </p>
            </div>

            <!-- Start Main Menu -->

            <div
                class="flex flex-col overflow-x-hidden w-full h-5/6 scrollYStyle pb-4 transition-all"
            >
                <TransitionGroup name="slide-fade">
                    <div
                        v-for="(name, index) in $page.props.pages.mainPages"
                        :key="name"
                        v-show="index < limit"
                        class="lg:ml-8 flex ml-1 h-8 text-white mt-7 lg:justify-start justify-center"
                    >
                        <Link :href="'/' + name.p_route" class="flex">
                            <img
                                v-if="name.p_icon == null"
                                src="../../../public/img/Dashboard.png"
                                alt=""
                                class="w-6 h-6"
                                :class="
                                    active == 1 ? 'opacity-100' : 'opacity-30'
                                "
                            />
                            <img
                                v-else
                                :src="name.p_icon"
                                alt=""
                                class="w-6 h-6"
                                :class="
                                    active == 1 ? 'opacity-100' : 'opacity-30'
                                "
                            />
                            <span
                                class="text-md ml-5 pb-1 lg:block text-lg hidden transition-all duration-100 hover:opacity-100"
                                :class="
                                    showMenu
                                        ? active == 1
                                            ? 'opacity-100'
                                            : 'opacity-30'
                                        : 'opacity-0'
                                "
                                >{{ name.p_name }}</span
                            >
                        </Link>
                    </div>
                </TransitionGroup>

                <div
                    class="h-9 rounded-full p-2 mt-5 mx-auto text-white font-black text-xl bg-gray-700 hover:bg-gray-900 cursor-pointer"
                    @click="limit = limit == 4 ? $page.props.pages.mainPages.length : 4;setLimit()"
                >
                    <ion-icon
                        v-if="limit == 4"
                        name="chevron-down-outline"
                    ></ion-icon>
                    <ion-icon v-else name="chevron-up-outline"></ion-icon>
                </div>

                <p
                    class="mx-auto mt-5 opacity-30 border border-dashed transition-all duration-500"
                    :class="showMenu ? ' w-32' : ' w-14'"
                ></p>

                <div
                    v-for="name in $page.props.pages.subPages"
                    :key="name"
                    class="lg:ml-8 flex flex-row ml-1 h-8 text-white mt-7 lg:justify-start justify-center"
                >
                    <Link :href="'/' + name.p_route" class="flex">
                        <img
                            v-if="name.p_icon == null"
                            src="../../../public/img/Dashboard.png"
                            alt=""
                            class="w-6 h-6"
                            :class="active == 1 ? 'opacity-100' : 'opacity-30'"
                        />
                        <img
                            v-else
                            :src="name.p_icon"
                            alt=""
                            class="w-6 h-6"
                            :class="active == 1 ? 'opacity-100' : 'opacity-30'"
                        />
                        <span
                            class="text-md ml-5 pb-1 lg:block hidden transition-all duration-100 hover:opacity-100"
                            :class="
                                showMenu
                                    ? active == 1
                                        ? 'opacity-100'
                                        : 'opacity-30'
                                    : 'opacity-0'
                            "
                            >{{ name.p_name }}</span
                        >
                    </Link>
                </div>
                <div
                    class="lg:ml-8 flex flex-row ml-1 h-8 text-white mt-7 lg:justify-start justify-center"
                >
                    <Link href="/logout" class="flex">
                        <img
                            src="../../../public/img/Logout.png"
                            alt=""
                            class="w-6 h-6 opacity-100 hover:opacity-100"
                        />
                        <span
                            class="text-base ml-5 lg:block hidden transition-all duration-100 font-semibold"
                            :class="showMenu ? 'opacity-100' : 'opacity-0'"
                            >Logout</span
                        >
                    </Link>
                </div>
            </div>

            <!-- End Main Menu -->
        </div>
    </div>
</template>

<style scoped>
.slide-fade-enter-active {
    transition: all 0.2s ease-in-out;
}

.slide-fade-leave-active {
    transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transition: all 0.2s ease-in-out;
    transform: translateY(20px);
    opacity: 0;
}
</style>
