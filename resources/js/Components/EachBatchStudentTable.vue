<template>
 <div class="custombackgroundcolor h-48   rounded-lg  mt-3 px-3 py-4 overflow-y-scroll my-5">
  <table  class="text-white w-full">
    <thead class="">
    <tr class="opacity-70 sm:text-sm customfontsize ">
        <th class="text-start pl-8">NAME</th>
        <th class="">Phone</th>
        <th >Address</th>
        <th>Age</th>
        <th >Detail</th>
    </tr>
    </thead>
    <tbody class="lg:text-sm text-xs customfontsize overflow-y-scroll">
    <tr class="customborder">
        <td class="text-start  py-1"><input type="checkbox" name="checkbox" class=""/> Thazin Aung</td>
        <td  class="text-center ">0932445232</td>
        <td class="text-center ">Yangon</td>
        <td  class="text-center">25</td>
        <td  class="text-center customtextcolor7 underline">View</td>
    </tr>
    <tr class="customborder">
        <td class="text-start  py-1"><input type="checkbox" name="checkbox" class=""/> Thazin Aung</td>
        <td  class="text-center ">0932445232</td>
        <td class="text-center ">Yangon</td>
        <td  class="text-center">25</td>
        <td  class="text-center customtextcolor7 underline">View</td>
    </tr>
    <tr class="customborder">
        <td class="text-start  py-1"><input type="checkbox" name="checkbox" class=""/> Thazin Aung</td>
        <td  class="text-center ">0932445232</td>
        <td class="text-center ">Yangon</td>
        <td  class="text-center">25</td>
        <td  class="text-center customtextcolor7 underline">View</td>
    </tr>
    <tr class="customborder">
        <td class="text-start  py-1"><input type="checkbox" name="checkbox" class=""/> Thazin Aung</td>
        <td  class="text-center ">0932445232</td>
        <td class="text-center ">Yangon</td>
        <td  class="text-center">25</td>
        <td  class="text-center customtextcolor7 underline">View</td>
    </tr>
    <tr class="customborder">
        <td class="text-start  py-1"><input type="checkbox" name="checkbox" class=""/> Thazin Aung</td>
        <td  class="text-center ">0932445232</td>
        <td class="text-center ">Yangon</td>
        <td  class="text-center">25</td>
        <td  class="text-center customtextcolor7 underline">View</td>
    </tr>
    <tr class="customborder">
        <td class="text-start  py-1"><input type="checkbox" name="checkbox" class=""/> Thazin Aung</td>
        <td  class="text-center ">0932445232</td>
        <td class="text-center ">Yangon</td>
        <td  class="text-center">25</td>
        <td  class="text-center customtextcolor7 underline">View</td>
    </tr>
    </tbody>
  </table>
  </div>
</template>

<script>
export default {

}
</script>

<style scoped>
input[type="checkbox"] {
  font: inherit;
  color:#FFC652;
  width: 1.15em;
  height: 1.15em;
  transform: translateY(-0.075em);
  margin-right: 0.7em;
}
@media screen and (max-width: 640px) {
    .customfontsize{
        font-size: 0.6em !important;
      }
    }
</style>