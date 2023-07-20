<template>
<div class="md:w-6/12 w-11/12 overflow-hidden">
  <div class="flex flex-row justify-between ">
     <h3 class="text-white ">Active Class</h3>
     <div class="text-white text-xs ml-1 pt-1">SEE ALL</div>
  </div>
  <div class="custombackgroundcolor w-full max-h-max rounded-lg ml-0.5 mt-3  pl-3 py-4">
  <table  class="text-white w-11/12 ">
    <thead>
    <tr class="opacity-70 sm:text-sm customfontsize">
        <th class="text-start sm:pl-7 pl-0">NAME</th>
        <th >DAY</th>
        <th >TIME</th>
        <th >PERSON</th>
        <th >STATUS</th>
    </tr>
    </thead>
    <tbody class="lg:text-sm text-xs customfontsize">
    <tr class="customborder">
        <td class="text-start sm:pl-7 pl-0 py-1">JP Batch 8</td>
        <td  class="text-center py-1">Tue,Thur,Sat</td>
        <td  class="text-center py-1">18:00 - 20:00</td>
        <td  class="text-center py-1">25</td>
        <td  class="text-center py-1 customtextcolor5">Class Time</td>
    </tr>
    <tr class="customborder">
        <td class="text-start sm:pl-7 pl-0 py-1">JP Batch 8</td>
        <td  class="text-center py-1">Tue,Thur,Sat</td>
        <td  class="text-center py-1">18:00 - 20:00</td>
        <td  class="text-center py-1">25</td>
        <td  class="text-center py-1 customtextcolor6">After 3 hours</td>
    </tr>
   <tr class="customborder">
        <td class="text-start sm:pl-7 pl-0 py-1">JP Batch 8</td>
        <td  class="text-center py-1">Tue,Thur,Sat</td>
        <td  class="text-center py-1">18:00 - 20:00</td>
        <td  class="text-center py-1">25</td>
        <td  class="text-center py-1 customtextcolor7">Before 3 mins</td>
    </tr>
   <tr class="customborder">
        <td class="text-start sm:pl-7 pl-0 py-1">JP Batch 8</td>
        <td  class="text-center py-1">Tue,Thur,Sat</td>
        <td  class="text-center py-1">18:00 - 20:00</td>
        <td  class="text-center py-1">25</td>
        <td  class="text-center py-1 customtextcolor5">class time</td>
    </tr>
   <tr class="customborder">
        <td class="text-start sm:pl-7 pl-0 py-1">JP Batch 8</td>
        <td  class="text-center py-1">Tue,Thur,Sat</td>
        <td  class="text-center py-1">18:00 - 20:00</td>
        <td  class="text-center py-1">25</td>
        <td  class="text-center py-1 customtextcolor6">After 1 hours</td>
    </tr>
    </tbody>
  </table>
  </div>
  </div>
</template>

<script>
export default {

}
</script>

<style scoped>
@media screen and (max-width: 640px) {
    .customfontsize{
        font-size: 0.6em;
      }
    }
</style>