<script setup>

import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
const porps = defineProps({
    
    classdata: Number,
});

</script>
<template>
 <div class="custombackgroundcolor h-48   rounded-lg  mt-3 px-3 py-4 overflow-y-scroll">
  <table  class="text-white w-full ">
    <thead class="">
    <tr class="opacity-70 sm:text-sm customfontsize ">
        <th class="text-start sm:pl-4 pl-0 ">NAME</th>
        <th class="">Date</th>
        <th >Attach File</th>
        <th >Detail</th>
    </tr>
    </thead>
    <tbody class="lg:text-sm text-xs customfontsize overflow-y-scroll">
    <tr class="customborder">
        <td class="text-start sm:pl-4 pl-0 py-1">Web Developer day 1</td>
        <td  class="text-center ">2022/09/10</td>
        <td  class="text-center">3</td>
        <a :href="route('addvideo.edit',48)"><td  class="text-center customtextcolor7 underline">
           
            edita</td></a>
    </tr>
    <tr class="customborder">
        <td class="text-start sm:pl-4 pl-0 py-1">Web Developer day 1</td>
        <td  class="text-center ">2022/09/10</td>
        <td  class="text-center">3</td>
        <td  class="text-center customtextcolor7 underline">edit</td>
    </tr>
    <tr class="customborder">
        <td class="text-start sm:pl-4 pl-0 py-1">Web Developer day 1</td>
        <td  class="text-center ">2022/09/10</td>
        <td  class="text-center">3</td>
        <td  class="text-center customtextcolor7 underline">edit</td>
    </tr>
    <tr class="customborder">
        <td class="text-start sm:pl-4 pl-0 py-1">Web Developer day 1</td>
        <td  class="text-center ">2022/09/10</td>
        <td  class="text-center">3</td>
        <td  class="text-center customtextcolor7 underline">edit</td>
    </tr>
    <tr class="customborder">
        <td class="text-start sm:pl-4 pl-0 py-1">Web Developer day 1</td>
        <td  class="text-center ">2022/09/10</td>
        <td  class="text-center">3</td>
        <td  class="text-center customtextcolor7 underline">edit</td>
    </tr>
    <tr class="customborder">
        <td class="text-start sm:pl-4 pl-0 py-1">Web Developer day 1</td>
        <td  class="text-center ">2022/09/10</td>
        <td  class="text-center">3</td>
        <td  class="text-center customtextcolor7 underline">
            edit</td>
    </tr>
    </tbody>
  </table>

  </div>
  <a :href="route('addvideo.show', classdata)">
<button class="float-right pt-0.5 custommb sm:w-2/6 w-24 h-7 mt-5  text-white rounded-lg flex justify-center bg-blue-600 hover:bg-blue-700 active:bg-blue-900 cusmargin">
<img src="../../../public/img/bxs_video-plus.png" alt="" class="w-5 h-5 pt-0.5">
<span class="ml-2 sm:text-base text-xs sm:pt-0 pt-1">Add Video</span>
</button>
</a>
</template>

<script>
export default {

}
</script>

<style scoped>
.custommb{
    margin-bottom: 0.93em;
}
@media screen and (max-width: 640px) {
    .customfontsize{
        font-size: 0.6em;
      }
    }

</style>