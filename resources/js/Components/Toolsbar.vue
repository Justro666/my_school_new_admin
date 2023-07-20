<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

const props = defineProps({
    active: {
        type: Number
    }
})

</script>

<template>
    <div class="flex justify-between text-whiteTextColor">
        <div class="py-3 w-1/6 flex justify-center rounded-tl-xl" 
        :class="{'bg-secondaryBackground' : active == 1}"><Link href="/mailtool" class="text-lg">MAIL</Link></div>
        <div class="py-3 w-1/6 flex justify-center"
        :class="{'bg-secondaryBackground' : active == 2}"><Link href="/privacypolicyTool" class="text-lg">PRIVACY POLICY</Link></div>
        <div class="py-3 w-1/6 flex justify-center"
        :class="{'bg-secondaryBackground' : active == 3}"><Link href="/blogTool" class="text-lg">BLOG</Link></div>
        <div class="py-3 w-1/6 flex justify-center"
        :class="{'bg-secondaryBackground' : active == 4}"><Link href="/categoryTool" class="text-lg">CATEGORY</Link></div>
        <div class="py-2 w-1/6 flex justify-center rounded-tr-xl"
        :class="{'bg-secondaryBackground' : active == 5}"><Link href="/guideTool" class="text-lg">GUIDE</Link></div>
    </div>
</template>