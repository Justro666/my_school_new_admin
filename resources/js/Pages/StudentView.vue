<script setup>
import NavBar from "../Components/NavBar.vue";
import Header from "../Components/Header.vue";
import { Navigation, Pagination } from "swiper";
import { Link, Head } from "@inertiajs/inertia-vue3";
import { Swiper, SwiperSlide } from "swiper/vue";
import Chart from "../Components/LineChart.vue";
import { ref, provide } from "vue";
import moment from "moment";

// Import Swiper styles
import "swiper/css";
import "swiper/css/bundle";
//

let segment = ref(1);

/// student join class id
let allId = [];
// active exam id
let activeExamClassId = [];
// active attedance id
let attendClassId = [];
let processBar = [];
let examName = []; // exam name list for only user progress chart
let examMark = []; // exam name list for only user progress chart

let attendancePercentage = []; //attendance percentage for one class
let examPercentage = []; //exam percentage for one class
let currentOverall = []; //current overall score
let oneClasExamRank = []; // one classExam Rank
let activeIndex = ref(0);
let count = ref(0); // count for classes join
let examCount = ref(0); // count for exams

let allRank = {}; // all ranking object
let allRankPercentage = []; // rall ranking with percentage

// Studnet Line Chart

let stuClass = props.studenProfile[0].Class.split(",");
let days = [];
const forceRerender = (activeIndex) => {
    examMark = [];
    examName = [];

    if (oneClasExamRank[activeIndex][1].length > 1) {
        // result in oneClasExamRank[activeIndex][1]

        for (
            let index = 0;
            index < oneClasExamRank[activeIndex][1].length;
            index++
        ) {
            examMark.push(oneClasExamRank[activeIndex][1][index].mark);
            examName.push(oneClasExamRank[activeIndex][1][index].e_name);
        }

        // chartOptions.value[0].xaxis.categories = [examName];
        chartOptions.value = {
            ...chartOptions.value,
            ...{
                xaxis: {
                    categories: examName.length == 0 ? "No Exam" : examName,
                },
            },
        };
        series.value[0].data = examMark.length == 0 ? 0 : examMark;
    } else {
        examMark.push(oneClasExamRank[activeIndex][1][0].mark);
        examName.push(oneClasExamRank[activeIndex][1][0].e_name);

        series.value[0].data = examMark.length == 0 ? 0 : examMark;
        chartOptions.value = {
            ...chartOptions.value,
            ...{
                xaxis: {
                    categories: examName.length == 0 ? "No Exam" : examName,
                },
            },
        };
    }
};

const props = defineProps({
    classes: {
        type: Object,
    },
    studenProfile: {
        type: Object,
    },
    examRanks: {
        type: Object,
    },
    rank_mark: {
        type: Object,
    },
    all_ranks: {
        type: Object,
    },
    attendance: {
        type: Object,
    },
    one_class: {
        type: Object,
    },
    exam_percent: {
        type: Object,
    },
    overall_rank: {
        type: Object,
    },
    class_rank: {
        type: Array,
    },
});

var examRankk = [];

for (const key in props.examRanks) {
    examRankk.push(props.examRanks[key]);
}

// console.log(examRankk);
// console.log(props.examRanks);
// console.log(props.class_rank);
for (let index = 0; index < props.classes.length; index++) {
    allId.push(props.classes[index].id);
}
for (let index = 0; index < props.exam_percent.length; index++) {
    activeExamClassId.push(props.exam_percent[index].id);

    examPercentage.push(props.exam_percent[index]);

    currentOverall.push(Object.values(props.overall_rank)[index]);
    oneClasExamRank.push(props.class_rank[index]); // props.class_rank[index] -> examRankk
}

// console.log(oneClasExamRank);

for (let index = 0; index < props.attendance.length; index++) {
    attendClassId.push(props.attendance[index].class_id);
    attendancePercentage.push(props.attendance[index]);
}

// push extra array room in exam perctange
//main function

for (let index = 0; index < allId.length; index++) {
    if (allId.length != attendClassId.length) {
        if (
            allId[index] != attendClassId[index] &&
            !attendClassId.includes(allId[index])
        ) {
            attendClassId.push(allId[index]);
            attendClassId.sort();
            let active_attendance = {
                attend: 0,
                class_id: allId[index],
            };

            let handler1 = {};

            const proxy1 = new Proxy(active_attendance, handler1);
            attendancePercentage.push(proxy1);
        }
    }
    if (allId.length != activeExamClassId.length) {
        if (
            allId[index] != activeExamClassId[index] &&
            !activeExamClassId.includes(allId[index])
        ) {
            activeExamClassId.push(allId[index]);
            activeExamClassId.sort();
            let exam_percent = {
                exam: 0,
                id: allId[index],
            };

            let overall_ranks = {
                id: allId[index],
                ranks: 0,
            };

            let one_class_exams = {
                0: {
                    cid: allId[index],
                },
            };

            let handler1 = {};
            let handler2 = {};
            let handler3 = {};
            const proxy1 = new Proxy(exam_percent, handler1);
            const proxy2 = new Proxy(overall_ranks, handler2);
            const proxy3 = new Proxy(one_class_exams, handler3);
            examPercentage.push(proxy1);
            currentOverall.push(proxy2);
            oneClasExamRank.push(proxy3);
        }
    }
}

// array sorting for each class
examPercentage = Object.entries(examPercentage).sort(
    (a, b) => a[1].id - b[1].id
);
currentOverall = Object.entries(currentOverall).sort(
    (a, b) => a[1].id - b[1].id
);
oneClasExamRank = Object.entries(oneClasExamRank).sort(
    (a, b) => a[1][0].cid - b[1][0].cid
);
attendancePercentage = Object.entries(attendancePercentage).sort(
    (a, b) => a[1].class_id - b[1].class_id
);

//get class join count
count = props.classes.length;
examCount = Object.values(props.examRanks).length;

if (oneClasExamRank[activeIndex.value][1].length > 1) {
    // result in oneClasExamRank[activeIndex][1]
    for (
        let index = 0;
        index < oneClasExamRank[activeIndex.value][1].length;
        index++
    ) {
        examMark.push(oneClasExamRank[activeIndex.value][1][index].mark);
        examName.push(oneClasExamRank[activeIndex.value][1][index].e_name);
    }
} else {
    examMark.push(oneClasExamRank[activeIndex.value][1][0].mark);
    examName.push(oneClasExamRank[activeIndex.value][1][0].e_name);
}

// console.log(examName);

const chartOptions = ref({
    chart: {
        toolbar: {
            show: false,
        },
        zoom: {
            enabled: false,
        },
        id: "basic-bar",
    },
    grid: {
        borderColor: "#fff",
        row: {
            colors: ["#fff", ""],
            opacity: 0,
        },
    },
    title: {
        text: "Over All",
        style: {
            color: "#ffffff",
        },
        align: "left",
    },
    stroke: {
        curve: "smooth",
    },
    dataLabels: {
        enabled: true,
    },
    xaxis: {
        categories: examName.length == 0 ? "No Exam" : examName,
        title: {
            text: "Exam",
        },
        labels: {
            style: {
                colors: [
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                ],
            },
        },
        min: 0,
        max: 11,
    },
    yaxis: {
        title: {
            text: "Mark",
            style: {
                color: "#ffffff",
            },
        },
        labels: {
            style: {
                colors: [
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                    "#ffffff",
                ],
            },
        },

        min: 0,
        max: 11,
    },
});
const series = ref([
    {
        name: "series-1",
        data: examMark.length == 0 ? 0 : examMark,
    },
]);

window.history.forward();

let showMenu = ref(true);
provide("showMenu", showMenu);
</script>

<template>
    <!-------------------- Navbar&header -------------------->
    <NavBar active="3"> </NavBar>
    <Head title="View Student"></Head>
    <Header
        headername="View Student"
        class="w-full pr-36 transition-all duration-500"
        :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"
    />
    <!---------------- body ----------------------->
    <div
        class="absolute h-auto w-full overflow-x-hiddens headercustomleft top-32 bg-primaryBackground transition-all duration-500 pr-36"
        :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"
    >
        <div class="flex flex-col items-center">
            <!-- Student Card Start -->
            <div
                class="bg-elementBackground flex md:flex-row flex-col justify-center items-center md:justify-around p-5 w-full lg:w-3/6 mb-16 h-auto md:h-auto text-whiteTextColor rounded-3xl"
                v-for="data in studenProfile"
                :key="studenProfile"
            >
                <div class="bg-white md:w-0.5 md:h-5/6 w-5/6 h-0.5 mt-6"></div>
                <div class="items-center w-full md:3/6 md:space-y-4 space-y-2">
                    <h3 class="text-3xl font-bold justify-center flex mt-7">
                        {{ data.name }}
                    </h3>
                    <div class="items-center flex flex-row mb-2 md:gap-8">
                        <div class="w-2/6 md:justify-end justify-start flex">
                            <p class="md:text-base text-sm font-semibold">
                                Class
                            </p>
                        </div>

                        <div
                            class="w-4/6 flex flex-col jstify-center text-sm text-yellowTextColor"
                        >
                            <ul v-for="classi in stuClass" :key="stuClass">
                                <li>{{ classi }}</li>
                            </ul>
                        </div>
                    </div>
                    <div
                        class="items-center flex flex-row mb-2 md:gap-8"
                        v-show="data.address"
                    >
                        <div class="w-2/6 md:justify-end justify-start flex">
                            <p class="md:text-base text-sm font-semibold">
                                Address
                            </p>
                        </div>

                        <div class="w-4/6 flex flex-row jstify-center">
                            <p>{{ data.address }}</p>
                        </div>
                    </div>
                    <div
                        class="items-center flex flex-row mb-2 md:gap-8"
                        v-show="data.phone"
                    >
                        <div class="w-2/6 md:justify-end justify-start flex">
                            <p class="md:text-base text-sm font-semibold">
                                Ph No.
                            </p>
                        </div>

                        <div class="w-4/6 flex flex-row jstify-center">
                            <p>{{ data.phone }}</p>
                        </div>
                    </div>
                    <div
                        class="items-center flex flex-row mb-2 md:gap-8"
                        v-show="data.age"
                    >
                        <div class="w-2/6 md:justify-end justify-start flex">
                            <p class="md:text-base text-sm font-semibold">
                                Age
                            </p>
                        </div>

                        <div class="w-4/6 flex flex-row jstify-center">
                            <p>{{ data.age }}</p>
                        </div>
                    </div>
                    <div
                        class="items-center flex flex-row mb-2 md:gap-8"
                        v-show="data.email"
                    >
                        <div class="w-2/6 md:justify-end justify-start flex">
                            <p class="md:text-base text-sm font-semibold">
                                Email
                            </p>
                        </div>

                        <div class="w-4/6 flex flex-row jstify-center">
                            <p>{{ data.email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Card End -->
            <div class="text-white w-11/12 text-4xl border-white border-t pt-6">
                <h2>{{ stuClass.length }} Class(es) Joined</h2>
            </div>
            <!-- Student Swiper Slide Start -->
            <swiper
                :slides-per-view="1"
                :space-between="50"
                :modules="[Navigation, Pagination]"
                navigation
                :pagination="{ clickable: true, dynamicBullets: ture }"
                grab-cursor
                class="w-5/6 md:4/6"
                @slideChange="
                    (event) => {
                        activeIndex = event.activeIndex;
                        forceRerender(activeIndex);
                    }
                "
            >
                <swiper-slide v-for="n in count" :key="n" :virtual-index="n">
                    <div
                        class="p-4 md:p-8 lg:w-10/12 xl:w-8/12 md:w-5/6 w-5/6 mx-auto h-auto"
                    >
                        <div
                            class="flex flex-col bg-elementBackground rounded-3xl lg:space-y-14 space-y-10 text-white md:p-8 p-5 w-full mb-4 md:mb-0 overflow-hidden card"
                        >
                            <div
                                class="flex flex-col justify-between items-center"
                            >
                                <div class="flex flex-col space-y-3">
                                    <h1
                                        class="font-medium text-lg md:text-4xl tracking-wide"
                                    >
                                        {{ props.studenProfile[0].name }}
                                    </h1>
                                </div>
                                <div
                                    class="relative flex flex-col lg:flex-row space-y-3 items-center justify-between w-full"
                                >
                                    <div
                                        class="md:flex flex-col items-center justify-center hidden"
                                    >
                                        <p
                                            class="font-bold text-xl md:text-4xl"
                                            :class="{
                                                'text-green-500':
                                                    Math.floor(
                                                        attendancePercentage[
                                                            activeIndex
                                                        ][1].attend * 100
                                                    ) == 100,
                                                'text-yellow-500':
                                                    Math.floor(
                                                        attendancePercentage[
                                                            activeIndex
                                                        ][1].attend * 100
                                                    ) < 99 &&
                                                    Math.floor(
                                                        attendancePercentage[
                                                            activeIndex
                                                        ][1].attend * 100
                                                    ) > 75,
                                                'text-red-500':
                                                    Math.floor(
                                                        attendancePercentage[
                                                            activeIndex
                                                        ][1].attend * 100
                                                    ) < 75,
                                            }"
                                        >
                                            {{
                                                attendancePercentage.length == 0
                                                    ? 0
                                                    : Math.floor(
                                                          attendancePercentage[
                                                              activeIndex
                                                          ][1].attend * 100
                                                      )
                                            }}%
                                        </p>
                                        <p
                                            class="text-lg md:text-xl font-medium md:font-semibold"
                                        >
                                            Attendance
                                        </p>
                                    </div>
                                    <div
                                        class="relative flex flex-row items-center justify-center"
                                    >
                                        <svg
                                            width="80"
                                            height="90"
                                            viewBox="0 0 114 130"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M33.21 4.55C42.0257 2.15313 51.3938 0 57 0C62.6063 0 71.9744 2.15313 80.79 4.55C89.8088 6.9875 98.9007 9.87187 104.247 11.6187C106.482 12.3569 108.464 13.7107 109.964 15.5244C111.465 17.3382 112.423 19.5385 112.729 21.8725C117.572 58.2481 106.335 85.2069 92.7013 103.041C86.9199 110.67 80.0262 117.389 72.2507 122.972C69.562 124.904 66.7133 126.603 63.7357 128.05C61.4607 129.122 59.015 130 57 130C54.985 130 52.5475 129.122 50.2644 128.05C47.2867 126.603 44.438 124.904 41.7494 122.972C33.974 117.389 27.0804 110.67 21.2988 103.041C7.66504 85.2069 -3.57183 58.2481 1.27067 21.8725C1.57688 19.5385 2.53527 17.3382 4.03574 15.5244C5.53622 13.7107 7.51791 12.3569 9.75317 11.6187C17.5141 9.07407 25.3353 6.71714 33.21 4.55Z"
                                                fill="#FFC652"
                                            />
                                        </svg>
                                        <p
                                            class="text-4xl font-bold rank absolute text-blackTextColor"
                                        >
                                            {{
                                                currentOverall.length == 0
                                                    ? 0
                                                    : currentOverall[
                                                          activeIndex
                                                      ][1].ranks
                                            }}
                                        </p>
                                    </div>
                                    <div
                                        class="flex flex-col items-center justify-center md:hidden"
                                    >
                                        <p
                                            class="font-bold text-xl md:text-4xl"
                                            :class="{
                                                'text-green-500':
                                                    Math.floor(
                                                        attendancePercentage[
                                                            activeIndex
                                                        ][1].attend * 100
                                                    ) == 100,
                                                'text-yellow-500':
                                                    Math.floor(
                                                        attendancePercentage[
                                                            activeIndex
                                                        ][1].attend * 100
                                                    ) < 99 &&
                                                    Math.floor(
                                                        attendancePercentage[
                                                            activeIndex
                                                        ][1].attend * 100
                                                    ) > 75,
                                                'text-red-500':
                                                    Math.floor(
                                                        attendancePercentage[
                                                            activeIndex
                                                        ][1].attend * 100
                                                    ) < 75,
                                            }"
                                        >
                                            {{
                                                attendancePercentage.length == 0
                                                    ? 0
                                                    : Math.floor(
                                                          attendancePercentage[
                                                              activeIndex
                                                          ][1].attend * 100
                                                      )
                                            }}%
                                        </p>
                                        <p
                                            class="text-lg md:text-xl font-medium md:font-semibold"
                                        >
                                            Attendance
                                        </p>
                                    </div>
                                    <div
                                        class="flex flex-col items-center justify-center"
                                    >
                                        <p
                                            class="font-bold text-xl md:text-4xl"
                                            :class="{
                                                'text-green-500':
                                                    Math.floor(
                                                        examPercentage[
                                                            activeIndex
                                                        ][1].exam
                                                    ) == 100,
                                                'text-yellow-500':
                                                    Math.floor(
                                                        examPercentage[
                                                            activeIndex
                                                        ][1].exam
                                                    ) < 99 &&
                                                    Math.floor(
                                                        examPercentage[
                                                            activeIndex
                                                        ][1].exam
                                                    ) > 75,
                                                'text-red-500':
                                                    Math.floor(
                                                        examPercentage[
                                                            activeIndex
                                                        ][1].exam
                                                    ) < 75,
                                            }"
                                        >
                                            {{
                                                examPercentage.length == 0
                                                    ? 0
                                                    : Math.floor(
                                                          examPercentage[
                                                              activeIndex
                                                          ][1].exam
                                                      )
                                            }}%
                                        </p>
                                        <p
                                            class="text-base sm:text-lg md:text-xl font-medium md:font-semibold"
                                        >
                                            Exam Mark
                                        </p>
                                    </div>
                                </div>
                                <div
                                    class="mt-10 flex justify-center items-center space-y-4"
                                >
                                    <div class="text-xl font-semibold">
                                        {{ props.classes[n - 1].c_name }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </swiper-slide>
            </swiper>

            <!-- Student Swiper Slide End -->
            <!-- Student Table Start -->

            <div
                class="md:w-4/6 w-full flex flex-col h-5/6 mt-10"
                v-show="oneClasExamRank[activeIndex][1].length > 0"
            >
                <div class="w-full h-full flex flex-row text-white text-center">
                    <!-- <button @click="hello()">Tabe change</button> -->
                    <div
                        :class="{ 'bg-elementBackground': segment == 1 }"
                        class="md:w-4/6 w-full h-12 rounded-tl-xl pt-3 md:text-xl cursor-pointer"
                        @click="segment = 1"
                    >
                        Overall Table
                    </div>
                    <div
                        :class="{ 'bg-elementBackground': segment == 2 }"
                        class="md:w-4/6 w-full h-12 rounded-tr-xl pt-3 md:text-xl cursor-pointer"
                        @click="segment = 2"
                    >
                        Self Rank
                    </div>
                </div>
                <div
                    v-if="segment == 1"
                    class="overflow-x-auto relative w-full"
                >
                    <table class="w-full text-sm text-left text-whiteTextColor">
                        <thead
                            class="text-lg font-semibold uppercase bg-elementBackground"
                        >
                            <tr>
                                <th scope="col" class="py-3 px-6">Date</th>
                                <th scope="col" class="py-3 px-6">Exam</th>
                                <th scope="col" class="py-3 px-6">Mark</th>
                                <th scope="col" class="py-3 px-6">Rank</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                class="bg-elementBackground"
                                v-for="result in oneClasExamRank[
                                    activeIndex
                                ][1]"
                            >
                                <td class="py-4 px-6">
                                    {{
                                        moment(result.date_submitted).format(
                                            "MMM D"
                                        )
                                    }}
                                </td>
                                <td class="py-4 px-6">{{ result.e_name }}</td>
                                <td
                                    class="py-4 px-6"
                                    :class="{
                                        'text-green-500': result.mark == 10,
                                        'text-red-500': result.mark <= 5,
                                        'text-yellow-500':
                                            result.mark > 5 && result.mark < 10,
                                    }"
                                >
                                    {{ result.mark }}
                                </td>
                                <td class="py-4 px-6">{{ result.rank }}</td>
                            </tr>
                        </tbody>
                        <!-- <tbody
                            v-else-if="
                                oneClasExamRank[activeIndex][1].length == 1
                            "
                        >
                            <tr
                                class="bg-elementBackground"
                                v-for="result in oneClasExamRank[
                                    activeIndex
                                ][1]"
                            >
                                <td class="py-4 px-6">
                                    {{
                                        moment(result.date_submitted).format(
                                            "MMM D"
                                        )
                                    }}
                                </td>
                                <td
                                    class="py-3 w-48 text-left font-bold dark:text-whiteTextColor"
                                >
                                    {{ result.e_name }}
                                </td>
                                <td
                                    class="py-4 px-6"
                                    :class="{
                                        'text-green-500': result.mark == 10,
                                        'text-red-500': result.mark <= 5,
                                        'text-yellow-500':
                                            result.mark > 5 && result.mark < 10,
                                    }"
                                >
                                    {{ result.mark }}
                                </td>

                                <td class="py-4 px-6">
                                    {{ result.rank }}
                                </td>
                            </tr>
                        </tbody> -->
                    </table>
                </div>
            </div>
            <!-- Student Table End -->
            <!-- Student Self Rank Chart Start-->
            <div
                v-if="segment == 2"
                v-show="oneClasExamRank[activeIndex][1].length > 0"
                class="md:w-4/6 w-full relative flex flex-col h-96 bg-elementBackground justify-center items-center"
            >
                <Chart :options="chartOptions" :series="series" class="chart" />
                <!-- <div
                    v-if="segment == 2"
                    v-show="oneClasExamRank[activeIndex][1].length > 0"
                    class="md:w-4/6 absolute w-full flex flex-col h-96 justify-center items-center"
                ></div> -->
            </div>

            <div
                class="flex justify-center item-center text-white w-5/6 h-4/6 text-xl mt-9"
                v-show="examPercentage[activeIndex][1].exam == 0"
            >
                No Exam Result !!
            </div>
            <!-- Student Self Rank Chart end-->
            <div class="w-4/6 text-white justify-start mt-5 mb-10">
                <button>
                    <Link
                        :href="route('student.index')"
                        class="underline underline-offset-4 hidden md:block"
                        >back
                    </Link>
                </button>
            </div>
        </div>
    </div>
</template>
