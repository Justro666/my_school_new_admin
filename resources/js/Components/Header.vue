<script setup>
import { Link } from "@inertiajs/inertia-vue3";
</script>
<template>
    <div class="fixed h-32 bg-primarybackground pt-5">
        <div class="flex justify-between">
            <div class="">
                <div class="text-white text-xl flex flex-row">
                    <span>{{ year }}/{{ month }}/{{ date }}</span>
                    <span class="ml-1"
                        >{{ hour }}:{{ minute }}:{{ seconds }}</span
                    >
                    <span class="ml-1">({{ day }})</span>
                </div>
                <div class="flex flex-row mt-10">
                    <div
                        class="text-white font-bold sm:text-2xl text-sm"
                        :class="{ dpnone: classaddmode }"
                    >
                        {{ headername }}
                    </div>
                </div>
            </div>

            <div class="flex">
                <div class="relative pr-5">
                    <Link href="/contact">
                        <div
                            v-show="$page.props.notiCount !== 0"
                            class="absolute top-2 right-5 translate-x-1/4 -translate-y-1/2 rounded-full bg-red-700 px-1 text-center text-xs text-white"
                        >
                            {{ $page.props.notiCount }}
                        </div>
                        <div
                            class="flex items-center justify-center rounded-lg text-center text-white shadow-lg dark:text-gray-200"
                        >
                            <img
                                src="../../../public/img/clarity_notification-solid.png"
                                alt=""
                                class="scale-75"
                            />
                        </div>
                    </Link>
                </div>

                <div class="flex text-white">
                    <div class="">
                        <div class="text-xl">
                            {{ $page.props.auth.username }}
                        </div>
                        <div
                            class="opacity-80 text-right font-semibold sm:text-base md:text-lg text-yellowTextColor"
                        >
                            {{ $page.props.auth.userrole }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            condition: true,
            year: 0,
            month: 0,
            date: 0,
            day: "",
            hour: 0,
            minute: 0,
            seconds: 0,
            message: "",
        };
    },
    methods: {
        gettime() {
            setInterval(() => {
                var today = new Date();
                this.seconds = today.getSeconds();
                this.minute = today.getMinutes();
                this.hour = today.getHours();
                this.year = today.getFullYear();
                this.month = today.getMonth() + 1;
                this.date = today.getDate();
                var days = today.getDay();
                switch (days) {
                    case 0:
                        this.day = "SUN";
                        break;

                    case 1:
                        this.day = "MON";
                        break;

                    case 2:
                        this.day = "TUE";
                        break;

                    case 3:
                        this.day = "WED";
                        break;

                    case 4:
                        this.day = "THU";
                        break;

                    case 5:
                        this.day = "FRI";
                        break;

                    case 6:
                        this.day = "SAT";
                        break;
                }
            }, 1000);
        },
        show() {},
    },
    props: {
        classid: Number,
        headername: String,
        classviewmode: Boolean,
        classaddmode: Boolean,
    },
    mounted() {
        this.gettime();
    },
};
</script>

<style scoped>
.btnblock {
    display: block !important;
}
.addclasscss {
    background-color: #2b2b2b;
    border: 1px solid white;
    border-radius: 1em;
    width: 52%;
}
.classnameinput {
    background-color: #2b2b2b;
    border-radius: 1em;
    border: #2b2b2b;
    width: 90%;
}
.classnameinput:focus {
    border: #2b2b2b;
}
.dpnone {
    display: none;
}
@media screen and (max-width: 640px) {
    .addclasscss {
        width: 90%;
    }
}
</style>
