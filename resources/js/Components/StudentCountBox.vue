<template>
  <div class="sm:w-44 xl:w-52 2xl:w-56 w-28 sm:h-24 h-16 rounded-lg customnavcolor shadow-lg text-center">
  <div class="sm:text-5xl text-2xl font-bold sm:mt-2.5 mt-1.5" :class="{customtextcolor1:studenttype=='All Students',
  customtextcolor2:studenttype=='Japanese',customtextcolor3:studenttype=='Web Development',
  customtextcolor4:studenttype=='JAVA SE'}">
    {{studentcount}}
</div>
  <div class="text-white opacity-30 sm:text-base text-xs">{{studenttype}}</div>

  </div>
</template>

<script>
export default {
      props: {
    studenttype: String,
    studentcount:Number
  }

}
</script>

<style>

</style>