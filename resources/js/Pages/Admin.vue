<script setup>
import NavBar from "../Components/NavBar.vue";
import Header from "../Components/Header.vue";
import Pagination from "../Components/Pagination.vue";
import { Inertia } from "@inertiajs/inertia";
import { ref, provide } from "vue";
import { useForm, Head } from "@inertiajs/inertia-vue3";
import { Link } from "@inertiajs/inertia-vue3";

const props = defineProps({
    admins: {
        type: Object,
    },
    sessionId: {
        Object,
    },
    mRole: Object,
});
console.log(props.admins);
console.log(props.sessionId);
let roleList = props.mRole.map((item) => item.id);

const roleCat = props.mRole.map((item) => {
    let obj = {
        roleId: item.id,
        checked: true,
    };
    return obj;
});

let mergeRole = ref({ ...props.admins.data });

let selectfunction = (data) => {
    for (let index = 0; index < roleCat.length; index++) {
        if (roleCat[index].roleId == data) {
            roleCat[index].checked = !roleCat[index].checked;
            roleCat[index].checked
                ? roleList.push(roleCat[index].roleId)
                : roleList.splice(roleList.indexOf(data), 1);
        }
    }

    mergeRole.value = props.admins.data.filter((item) =>
        roleList.includes(item.roleid)
    );
};
// for (let index = 0; index < props.admins.data.length; index++) {
//     mergeRole.push(props.admins.data[index]);
//     mergeRole[mergeRolngth-1]=[ mergeRoleadmins.data[index].id]
//     for (let i = 0; i < mergeRole.length; i++) {
//         if(mergeRole[i].id == props.admins.data[i].id){
//             if(!mergeRole[i].include(props.admins.data[i].id)){
//                 mergeRole[i].id.push()
//             }
//         }

//     }
// }
const showMenu = ref(true);
window.history.forward();
provide("showMenu", showMenu);
</script>

<template>
    <Head title="Admin" />
    <NavBar active="5"> </NavBar>
    <Header
        headername="Admin"
        class="w-full pr-36 transition-all duration-500"
        :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"
    />
    <div
        class="absolute h-auto w-full py-5 headercustomleft top-32 bg-primaryBackground rounded-lg mt-10 ml-5 customblack transition-all duration-500 pr-36"
        :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"
    >
        <div class="pb-7">
            <span v-for="category in mRole" :key="category" class="ml-2">
                <input
                    type="checkbox"
                    class="css-checkbox"
                    :id="category.id"
                    checked="checked"
                    :value="category.id"
                    @click="selectfunction(category.id)"
                />
                <label
                    :for="category.id"
                    class="css-label lite-gray-check sm:text-base text-xs text-white"
                    >{{ category.r_name }}</label
                >
            </span>
        </div>
        <div
            class="px-4 w-full h-auto p-8 relative bg-elementBackground rounded-lg"
        >
            <div class="px-4 w-full">
                <table
                    class="text-white w-full bg-elementBackground rounded-lg mt-5"
                >
                    <tr class="opacity-70 customfontsize">
                        <th class="text-start pt-4 pl-10">NAME</th>
                        <th class="pt-4">EMAIL</th>
                        <th class="pt-4">ROLE</th>
                        <th class="pt-4">STATUS</th>
                        <th class="pt-4">SETTING</th>
                    </tr>
                    <tbody class="text-sm customfontsize mt-3">
                        <tr
                            class="border-b"
                            v-for="admin in mergeRole"
                            :key="admin"
                            v-show="!admin.same"
                        >
                            <td class="text-start pl-10 py-4">
                                {{ admin.name }}
                            </td>
                            <td class="text-center text-yellowTextColor py-4">
                                {{ admin.email }}
                            </td>
                            <td class="text-center py-4">
                                {{ admin.role }}
                            </td>
                            <td
                                class="text-center py-4 text-green-600"
                                v-if="admin.delete == 0"
                            >
                                Active
                            </td>
                            <td
                                class="text-center py-4 text-redTextColor"
                                v-else
                            >
                                Suspended
                            </td>
                            <td
                                class="text-center text-yellowTextColor underline py-4"
                            >
                                <Link
                                    :href="route('admin.edit', admin.adminId)"
                                >
                                    Edit
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="flex items-center justify-center my-5">
                    <Pagination :links="admins.links"> </Pagination>
                </div>

                <div class="flex py-8 justify-end w-full">
                    <div class="px-5">
                        <Link href="/adminPermission">
                            <button
                                class="py-2 px-5 text-whiteTextColor text-md bg-blueTextColor rounded-xl flex items-center"
                            >
                                <img
                                    src="../../../public/img/fluent-mdl2_permissions-solid.png"
                                    alt=""
                                    class="w-5 h-5 pt-0.5"
                                />
                                <span class="mx-2">Permission</span>
                            </button>
                        </Link>
                    </div>
                    <div class="px-5">
                        <Link :href="route('admin.create')">
                            <button
                                class="py-2 px-5 text-whiteTextColor text-md bg-blueTextColor rounded-xl flex items-center"
                                type="button"
                            >
                                <img
                                    src="../../../public/img/addlogo.png"
                                    alt=""
                                    class="w-5 h-5 pt-0.5"
                                />

                                <span class="mx-2">Add Admin</span>
                            </button>
                        </Link>
                    </div>
                    <!-- <div>{{$page.props.auth.userrole}}</div> -->
                </div>
            </div>
        </div>
    </div>
</template>

<style></style>
