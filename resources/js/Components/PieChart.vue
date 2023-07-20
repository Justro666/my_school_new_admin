<template>
      <apexcharts
        :width="width"
        :height="height"
        :options="options"
        :series="series"
      ></apexcharts>
  </template>
  
  <script>
  import VueApexCharts from "vue3-apexcharts";
  
  export default {
    name: "PieChart",
    props: {
      width: {
        type: Number,
      },
      height: {
        type: Number,
      },
    },
    components: {
        apexcharts: VueApexCharts,
    },
  };
  </script>

 