<template>
  <div class="customnavcolor w-full text-white p-4 rounded-lg">
   <h3 class="sm:text-lg text-base">Class Information</h3>
   <div class="float-right">
   <img src="../../../public/img/php-developer.jpg" alt="" class="rounded-full sm:w-20 w-14 border-white border-2">
   <button class=" sm:w-20 w-14 bg-blue-600 hover:bg-blue-700 active:bg-blue-900 sm:text-base text-xs rounded-lg p-1 mt-4">Upload</button>
   </div>
   <div class="sm:text-2xl text-lg font-bold mt-2">Web Developer Batch 9</div>
   <textarea name="" id=""  class="text-white w-10/12 mt-2 sm:text-sm text-xs customnavcolor rounded-xl customborder"></textarea>
   <div class="flex sm:flex-row flex-col justify-between w-10/12 text-sm mt-3">
  
    <div>
        <div>Date :
            
            <span>
            <input type="text" class="customnavcolor text-white sm:text-sm text-xs rounded-lg sm:w-1/4 w-16 customborder">
            </span>
            -
            <span>
            <input type="text" class="customnavcolor text-white sm:text-sm text-xs rounded-lg sm:w-1/4 w-16 customborder">
        </span> 
        </div>
      <div class="mt-3  flex flex-row ">Day: 
       <span class="flex flex-wrap mt-0.5 sm:text-sm text-xs">  
       <input type="checkbox" name="checkbox" class="mt-0.5 ml-3"/>
       <label for="" class="ml-2">SUN</label>
       
       <input type="checkbox" name="checkbox" class="mt-0.5 ml-3"/>
       <label for="" class="ml-2">Mon</label>

       <input type="checkbox" name="checkbox" class="mt-0.5 ml-3"/>
       <label for="" class="ml-2">TUE</label>

       <input type="checkbox" name="checkbox" class="mt-0.5 ml-3"/>
       <label for="" class="ml-2">WED</label>

       <input type="checkbox" name="checkbox" class="mt-0.5 ml-3"/>
       <label for="" class="ml-2">THU</label>

     <input type="checkbox" name="checkbox" class="mt-0.5 ml-3"/>
       <label for="" class="ml-2">FRI</label>

       <input type="checkbox" name="checkbox" class="mt-0.5 ml-3"/>
       <label for="" class="ml-2">SAT</label>

       </span>

        </div>
        <div class="mt-3">Time : 
           <span>
            <input type="text" class="customnavcolor text-white sm:text-sm text-xs rounded-lg sm:w-1/4 w-16 customborder">
            </span>
            -
            <span>
            <input type="text" class="customnavcolor text-white sm:text-sm text-xs rounded-lg sm:w-1/4 w-16 customborder">
            </span>
        </div>
        <div class="mt-3">Person : <span>20</span></div>
    </div>
    <div>
        <div class="sm:mt-0 mt-3">Instructor : 
        <span class="">
        <select id="sorttype" name="status" class="customnavcolor sm:text-sm text-xs sm:w-52 w-32 text-white border-white rounded-xl">
        <option value="status" class="sm:text-sm text-xs">Linn Ko Ko</option>
        <option value="name" class="sm:text-sm text-xs">Thein Naing</option>
        <option value="person" class="sm:text-sm text-xs">Pyi Soe</option>
        </select>
        </span>
        </div>
        <div class="mt-3">Category :
        <span class="">
        <select id="sorttype" name="status" class="customnavcolor sm:text-sm text-xs text-white border-white rounded-xl sm:w-52 w-32">
        <option value="status" class="sm:text-sm text-xs">Web Developement</option>
        <option value="name" class="sm:text-sm text-xs">Japanese</option>
        <option value="person" class="sm:text-sm text-xs">Java</option>
        </select>
        </span>
        </div>
        <div class="mt-3">Fees : <span class="pl-7"><input type="text" class="customnavcolor text-white sm:text-sm text-xs rounded-lg sm:w-52 w-32 customborder"></span></div>
    </div>
   </div>
  </div>
</template>

<script>
export default {

}
</script>

<style scoped>
input[type="checkbox"] {
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
input[type="checkbox"]::before {
  content: "";
  width: 1.15em;
  height: 1.15em;
  border-radius: 50%;
  transform: scale(0);
  transition: 120ms transform ease-in-out;
  box-shadow: inset 1em 1em #304BFC;
}

input[type="checkbox"]:checked::before {
  transform: scale(1);
}
.customborder{
    border: 0.01em solid white;

}
</style>