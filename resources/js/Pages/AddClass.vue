<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import NavBar from "../Components/NavBar.vue";
import Header from "../Components/Header.vue";
import { ref } from '@vue/reactivity';
import throttle from "lodash/throttle";
import { Inertia } from "@inertiajs/inertia";
import { Field, Form, ErrorMessage } from 'vee-validate';
import axios from 'axios';
import { watch } from '@vue/runtime-core';
import { provide } from "vue";
import Loading from 'vue-loading-overlay';

var studentid = ref([]);
const props = defineProps({
    instructors : {
        type : Object
    },
    category : {
        type : Object
    },
    student : {
        type : Object
    },
        errors : {
        type : Object
    },

})

let isloading =ref(false);
let loader = ref('bars');
let color = ref('#000');
let width = ref('100')
let height = ref('200')
let fullPage = ref(true)

let searchstdname=ref();
const form = useForm({
    classnames: null,
    classimage: null,
    classinformation: null,
    startdate: null ,
    enddate:null ,
    day1 : 0 ,
    day2 : 0 ,
    day3 : 0 ,
    day4 :0 ,
    day5 : 0 ,
    day6 : 0 ,
    day7 : 0 ,
    classday:null,
    datecheck:null,
    timecheck:null,
    starttime : null ,
    endtime : null ,
    instructor: null ,
    categories:null ,
    fees: null ,
    students: null ,

});
const imgSrc = ref("");

var imgnoborder = true;
const onFile = (e) => {
    const files = e.target.files
    if (!files.length) return

    const reader = new FileReader()
    reader.readAsDataURL(files[0])
    var imgname=files[0].name
    reader.onload = () => (imgSrc.value = reader.result,imgnoborder=false)
    form.classimage=files[0];

}
const pfimgborder=()=>
{
 if(imgnoborder){
  return "profileimgnoborder"
 }
}
const submit = () => {
  form.students=studentid;
  form.classday=form.day1+form.day2+form.day3+form.day4+form.day5+form.day6+form.day7;
  form.datecheck=form.startdate+form.enddate;
  form.timecheck=form.starttime+form.endtime;
  form.post(route('class.store',form, {
    onStart:(data)=>{
            isloading.value = true ;
        },
        onFinish:(data)=>{
            isloading.value = false ;
        },
  }));

}


// var timepicker = new timepicker('.field', {
//   theme: 'dark',
//   lang: 'pt',
// });
// timepicker.on('change', function(evt) {

//   var value = (evt.hour || '00') + ':' + (evt.minute || '00');
//   evt.element.value = value;

// });



watch(
    searchstdname,
    throttle(function (value) {

        Inertia.get(
            "/class/create",
            { searchstdname: value },
            { preserveState: true, replace: true }
        );
    }, 200)
);

window.history.forward();

let showMenu = ref(true);
provide('showMenu', showMenu);
</script>

<template >
<!-------------------- Navbar&header -------------------->
<NavBar active=2> </NavBar>
<Header headername="Web Developer Batch 9" classaddmode=true class="w-full pr-36 transition-all duration-500" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'"/>

<loading v-model:active="isloading" :is-full-page="fullPage" :loader="loader" :color="color" :width="width" :height="height"/>

<!---------------- body ----------------------->
<div class="absolute w-full headercustomleft  top-32 customblack px-5 transition-all duration-500 pr-36" :class="showMenu ? 'ml-60 pl-4 pr-64' : 'ml-28'">
<form @submit.prevent="submit">
<div class="flex flex-row mt-10 addclasscss fixed sm:top-4 top-10 z-50" >
 <input type="text"  class="classnameinput  sm:text-xl text-sm font-bold text-white"  v-model="form.classnames" placeholder="Class Name">
        <button type="button" class="mt-2 sm:w-7 w-4 sm:h-7 h-4 border-2 sm:text-sm text-xs rounded-full border-solid border-white text-white " @click="form.classnames=''"> &#9587</button>
    </div>
<div v-if="errors.classnames" class="text-red-900">{{errors.classnames}}</div>
<div class="my-5">
    <div class="customnavcolor w-full text-white p-4 rounded-lg">
   <h3 class="sm:text-lg text-base">Class Information</h3>
   <div class="float-right">
   <img :src="imgSrc" alt=""  class="rounded-full sm:w-20 sm:h-20 h-14 w-14 classpp" :class="pfimgborder()" >
<label for="file-upload" class="custom-file-upload sm:w-20 w-14 bg-blue-600 hover:bg-blue-700 active:bg-blue-900 sm:text-base text-xs rounded-lg p-1 mt-4">
    Upload
</label>
<input id="file-upload" type="file" accept="image/*" @change="onFile"/>
<div v-if="errors.classimage" class="text-red-900 w-32 mt-1">{{errors.classimage}}</div>
   </div>
   <div class="sm:text-2xl text-lg font-bold mt-2" >{{ form.classnames }}</div>
   <textarea name="" id=""  v-model="form.classinformation"  class="text-white w-10/12 h-40 mt-2 sm:text-sm text-xs customnavcolor rounded-xl customborder1" placeholder="Class Detail"></textarea>
   <div v-if="errors.classinformation" class="text-red-900 mt-1 ml-3">{{errors.classinformation}}</div>
   <div class="flex sm:flex-row flex-col justify-between w-10/12 text-sm mt-3">

    <div>
        <div>Date :

            <span>
            <input type="date" v-model="form.startdate" class="customnavcolor text-white sm:text-sm text-xs rounded-lg sm:w-1/3 w-16 customborder1">
            </span>
            -
            <span>
            <input type="date" v-model="form.enddate" class="customnavcolor text-white sm:text-sm text-xs rounded-lg sm:w-1/3 w-16 customborder1">
            </span>
            <div v-if="errors.startdate" class="text-red-900 mt-1 ml-12">{{errors.startdate}}</div>
            <div v-if="errors.enddate" class="text-red-900 mt-1 ml-12">{{errors.enddate}}</div>
             <div v-if="errors.datecheck" class="text-red-900 mt-1 ml-12">{{errors.datecheck}}</div>
        </div>
      <div class="mt-3  flex flex-row ">Day:
       <span class="flex flex-wrap mt-0.5 sm:text-sm text-xs">
       <input type="checkbox" name="" v-model="form.day1" value="1" class="daycheckbox mt-0.5 ml-3"/>
       <label for="" class="ml-2">SUN</label>

       <input type="checkbox" name="checkbox" v-model="form.day2" value="1" class="daycheckbox mt-0.5 ml-3"/>
       <label for="" class="ml-2">Mon</label>

       <input type="checkbox" name="checkbox" v-model="form.day3" value="1" class="daycheckbox mt-0.5 ml-3"/>
       <label for="" class="ml-2">TUE</label>

       <input type="checkbox" name="checkbox" v-model="form.day4" value="1" class="daycheckbox mt-0.5 ml-3"/>
       <label for="" class="ml-2">WED</label>

       <input type="checkbox" name="checkbox" v-model="form.day5" value="1" class="daycheckbox mt-0.5 ml-3"/>
       <label for="" class="ml-2">THU</label>

     <input type="checkbox" name="checkbox" v-model="form.day6" value="1" class="daycheckbox mt-0.5 ml-3"/>
       <label for="" class="ml-2">FRI</label>

       <input type="checkbox" name="checkbox" v-model="form.day7" value="1" class="daycheckbox mt-0.5 ml-3"/>
       <label for="" class="ml-2">SAT</label>

       </span>

        </div>
        <div v-if="errors.classday" class="text-red-900 mt-1 ml-12">{{errors.classday}}</div>
        <div class="mt-3">Time :
           <span>
            <input type="time"  v-model="form.starttime" class="customnavcolor text-white sm:text-sm text-xs rounded-lg sm:w-1/3 w-16 customborder1" >
            </span>
            -
            <span>
            <input type="time" v-model="form.endtime" class="customnavcolor text-white sm:text-sm text-xs rounded-lg sm:w-1/3 w-16 customborder1">
            </span>
            <div v-if="errors.starttime" class="text-red-900 mt-1 ml-12">{{errors.starttime}}</div>
            <div v-if="errors.endtime" class="text-red-900 mt-1 ml-12">{{errors.endtime}}</div>
            <div v-if="errors.timecheck" class="text-red-900 mt-1 ml-12">{{errors.timecheck}}</div>
        </div>
        <div class="mt-3">Persons : <span>{{studentid.length}}</span></div>
    </div>
    <div>
        <div class="sm:mt-0 mt-3">Instructor :
        <span class="" >
        <select id="sorttype"  v-model="form.instructor" name="status" class="customnavcolor sm:text-sm text-xs sm:w-52 w-32 text-white border-white rounded-xl">
        <option v-for="item in instructors" :value="item.id" class="sm:text-sm text-xs">{{item.i_name}}</option>
        </select>
        </span>
        <div v-if="errors.instructor" class="text-red-900 mt-1 ml-20">{{errors.instructor}}</div>
        </div>
        <div class="mt-3">Category :
        <span class="">
        <select id="sorttype" name="status" v-model="form.categories" class="customnavcolor sm:text-sm text-xs text-white border-white rounded-xl sm:w-52 w-32">
        <option v-for="data in category" :value="data.id" class="sm:text-sm text-xs">{{data.c_name}}</option>
        </select>
        </span>
        <div v-if="errors.categories" class="text-red-900 mt-1 ml-20">{{errors.categories}}</div>
        </div>
        <div class="mt-3">Fees : <span class="pl-7"><input type="text" v-model="form.fees" class="customnavcolor text-white sm:text-sm text-xs rounded-lg sm:w-52 w-32 customborder1" placeholder="Class Fees"></span></div>
        <div v-if="errors.fees" class="text-red-900 mt-1 ml-20">{{errors.fees}}</div>
    </div>
   </div>
  </div>
</div>

<div  class="flex flex-row mb-3">
<h3 class="text-white pt-1 ">Student Name : </h3>
<input type="text" v-model="searchstdname" class="customnavcolor sm:ml-3 ml-2  text-white sm:text-sm text-xs rounded-lg sm:w-1/4 w-24 customborder1" placeholder="name">
</div>
<div class="sm:w-2/4 w-4/4 h-48 mt-3  overflow-y-scroll rounded-lg">
  <table  class="text-white w-full  custombackgroundcolor relative">
    <thead class="sticky top-0 custombackgroundcolor w-full z-50">
    <tr class="opacity-70  sm:text-sm customfontsize ">
        <th class="text-start pl-8">NAME</th>
        <th >Phone</th>
        <th >Address</th>
        <th>Age</th>
        <th >Detail</th>
    </tr>
    </thead>
    <tbody class="lg:text-sm text-xs customfontsize">
    <tr class="customborder" v-for="user in student" :key="user">

        <td class="text-start  py-1 pl-1"><label ><input type="checkbox"  aria-label="an appropriate label" :value="user.id" v-model="studentid" class="cuscheckbox"/> {{user.name}}</label></td>
        <td  class="text-center "> {{user.phone}}</td>
        <td class="text-center ">{{user.address}}</td>
        <td  class="text-center">{{user.age}}</td>
        <td class="text-center text-yellowTextColor underline">
            <Link :href="route('student.show', user.id)"
            >View</Link
            >
        </td>
    </tr>
    </tbody>
  </table>
</div>
<button type="submit" class=" pt-0.5 float-right  mb-3  w-28 h-7 mt-5  text-white rounded-lg flex justify-center bg-blue-600 hover:bg-blue-700 active:bg-blue-900 cusmargin">
<img src="../../../public/img/bx_save.png" alt="" class="w-5 h-5 pt-0.5">
<span class="ml-2 sm:text-base text-xs sm:pt-0 pt-1" >Save</span>
</button>
</form>
</div>
</template>
<style scoped>
input[type="checkbox"].daycheckbox {
  appearance: none;
  background-color: rgba(217, 217, 217, 0.36);
  font: inherit;
  color: currentColor;
  width: 1.15em;
  height: 1.15em;
  border-radius: 50%;
  transform: translateY(-0.075em);
  display: grid;
  place-content: center;
}
input[type="checkbox"].daycheckbox::before {
  content: "";
  width: 1.15em;
  height: 1.15em;
  border-radius: 50%;
  transform: scale(0);
  transition: 120ms transform ease-in-out;
  box-shadow: inset 1em 1em #304BFC;
}

input[type="checkbox"].daycheckbox:checked::before {
  transform: scale(1);
}
.customborder1{
    border: 0.01em solid white;

}
.customalign{
    margin-bottom: 1.3em;
}
input[type="checkbox"].cuscheckbox {
  font: inherit;
  color:#FFC652;
  width: 1.15em;
  height: 1.15em;
  transform: translateY(-0.075em);
  margin-right: 0.7em;
}
.addclasscss{
  background-color: #2B2B2B;
  border: 1px solid white;
  border-radius: 1em;
  width: 52%;
}
.classnameinput{
  background-color: #2B2B2B;
  border-radius: 1em;
  border: #2B2B2B;
  width: 90%;
}
.imginput{
  visibility: hidden;
}
.imginputdiv{
  background-color: #304BFC;
  overflow: hidden;
}
input[type="file"] {
    display: none;
}
.custom-file-upload {
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
}
.profileimgnoborder{
  border: white 2px solid;
}
img.classpp {
  position: relative;
}

/* style this to fit your needs */
/* and remove [alt] to apply to all images*/
img.classpp:after {
  display: block;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: #333333;
  content: attr(alt);
}
.tableheaderwidth{
  width: 48.65%;
}
@media screen and (max-width: 640px) {
    .customfontsize{
        font-size: 0.6em !important;
      }
    }
</style>
