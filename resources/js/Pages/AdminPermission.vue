<script setup>
import NavBar from "../Components/NavBar.vue";
import Header from "../Components/Header.vue";
import Pagination from "../Components/Pagination.vue";
import Notisuccess from "../Components/Notisuccess.vue";
import NotiError from "../Components/NotiError.vue";
import { Inertia } from "@inertiajs/inertia";
import { ref, provide } from "vue";
import { Link, useForm } from "@inertiajs/inertia-vue3";
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/css/index.css';

const props = defineProps({
    role_page: {
        type: Object,
    },
    permissionPages: {
        type: Object,
    },
});



let isloading = ref(false);
let loader = ref('bars');
let color = ref('#000');
let width = ref('100')
let height = ref('200')
let fullPage = ref(true)

const form = useForm({
    lists: null,
});

let checkList = [];

const pushToCheckList = (roleId, pageId) => {
    checkList.push({ roleId, pageId });
};

for (let i = 0; i < props.role_page.length; i++) {
    for (let j = 0; j < props.permissionPages.length; j++) {
        if (
            props.role_page[i].page.find((Obj) => Obj.roleId === props.permissionPages[j].pageId)
        ) {
            pushToCheckList(props.role_page[i].roleId, props.permissionPages[j].pageId);
        }
    }
}

const checkpage = (roleid, pageid) => {
    if (
        checkList.find(
            ({ roleId, pageId }) => roleId == roleid && pageId == pageid
        ) == undefined
    ) {
        checkList.push({
            roleId: roleid,
            pageId: pageid,
        });

    } else {
        const indexOfObject = checkList.findIndex((object) => {
            return object.roleId == roleid && object.pageId == pageid;
        });

        checkList.splice(indexOfObject, 1);
    }
};

let showNoti = ref(false);
let showError = ref(false);

const submit = () => {
    Inertia.post(route("adminPermission.store"), form, {
        onError: (data) => {
            
        },
        onSuccess: (data) => {
            showNoti.value = true;

            setTimeout(() => {
                showNoti.value = false;
            }, 2000);
        },
        onStart: (data) => {
            isloading.value = true;
        },
        onFinish: () => {
            isloading.value = false;

            showError.value = true;

            setTimeout(() => {
                showError.value = false;
            }, 2000);
        }
    });
};

form.lists = checkList;

window.history.forward();

let showMenu = ref(true);
provide('showMenu', showMenu);
</script>

<template>

    <Head title="Permission" />

    <NavBar />
    <Header headername="Admin Permission" class="w-full pr-36 transition-all duration-500"
        :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'" />
    <loading v-model:active="isloading" :is-full-page="fullPage" :loader="loader" :color="color" :width="width"
        :height="height" />

    <div class="absolute h-auto w-full overflow-x-hidden py-5 headercustomleft top-32 customblack transition-all duration-500 pr-36"
        :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'">
        <div class="absolute z-10 transition-all duration-500" v-if="showNoti" :class="showMenu ? 'right-64' : 'right-36'">
            <Notisuccess />
        </div>

        <div class="absolute z-10 transition-all duration-500" v-if="showError" :class="showMenu ? 'right-64' : 'right-36'">
            <NotiError />
        </div>

        <form @submit.prevent="submit">
            <div class="w-full h-auto p-8 relative bg-secondaryBackground rounded-xl flex flex-col space-y-5">
                <div class="w-full flex flex-start border border-white rounded-md overflow-hidden"
                    v-for="role in props.role_page" :key="role.roleId" :id="role.roleId">
                    <div class="flex items-center justify-center border-r border-white p-5 w-56">
                        <p class="text-white text-xl">
                            {{ role.roleName }}
                        </p>
                    </div>

                    <div class="flex items-center overflow-x-auto py-2 scrollXStyle">
                        <div class="flex items-center px-7 py-2" v-for="page in props.permissionPages" :key="page">
                            <div class="flex flex-col items-center mx-5">
                                <p class="text-white whitespace-nowrap">
                                    {{ page.pageName }}
                                </p>
                                <input type="checkbox" name="role.id[]" class="mt-1" checked v-if="
                                    role.page.find(
                                        (obj) => obj.id === page.pageId
                                    )
                                " @click="checkpage(role.roleId, page.pageId)" />
                                <input type="checkbox" @click="checkpage(role.roleId, page.pageId)" class="mt-1" v-else
                                    :id="page.pageId" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <div class="flex justify-center items-center my-10">
                    <Pagination :links="role_page.links"> </Pagination>
                </div> -->

                <div class="flex justify-between mt-5">
                    <div class="flex space-x-5">
                        <Link href="/pageList"
                            class="py-2 px-5 text-whiteTextColor text-sm bg-blueTextColor rounded-xl flex items-center">
                        <img src="../../../public/img/list.png" alt="" class="w-5 h-5 pt-0.5" />
                        <span class="mx-2">Page List</span>
                        </Link>
                        <Link href="/admin"
                            class="py-2 px-5 text-whiteTextColor text-sm bg-blueTextColor rounded-xl flex items-center">
                        <img src="../../../public/img/list.png" alt="" class="w-5 h-5 pt-0.5" />
                        <span class="mx-2">Admin List</span>
                        </Link>
                        <Link href="/addRole"
                            class="py-2 px-5 text-whiteTextColor text-sm bg-blueTextColor rounded-xl flex items-center">
                        <img src="../../../public/img/plus.png" alt="" class="w-5 h-5 pt-0.5" />
                        <span class="mx-2">Add Role</span>
                        </Link>
                    </div>
                    <button type="submit"
                        class="py-2 px-5 text-whiteTextColor text-sm bg-blueTextColor rounded-xl flex items-center">
                        <img src="../../../public/img/save.png" alt="" class="w-5 h-5 pt-0.5" />
                        <span class="mx-2">Save</span>
                    </button>
                </div>
            </div>
        </form>

        <!-- <div class="fixed left-42 bottom-5">
            <button>
                <a
                    href="/adminPermission"
                    class="underline underline-offset-4 hidden md:block text-whiteTextColor"
                    >back</a
                >
            </button>
        </div> -->
    </div>
</template>

<style>

</style>
