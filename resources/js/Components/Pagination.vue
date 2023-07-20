<template>
  <div v-if="links.length > 3">
    <div class="flex flex-wrap -mb-1">
      <template v-for="(link, key) in links" :key="key">
        <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4   text-gray-400 border dark:border-0 rounded dark:bg-darkSecondaryBackground"
          v-html="link.label" />
        <inertia-link v-else
          class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border dark:border-0 rounded hover:bg-primaryBackground dark:text-whiteTextColor hover:text-white dark:active:bg-secondaryBackground active:bg-secondaryBackground dark:hover:text-gray-400 dark:bg-darkSecondaryBackground"
          :class="{ 'bg-primaryBackground text-white dark:bg-secondaryBackground': link.active }" 
          :href="link.url"
           v-html="link.label" />
      </template>
    </div>
  </div>
</template>

<script>
import { InertiaLink } from "@inertiajs/inertia-vue3";

export default {
  props: {
    links: Array,
  },
  components: {
    InertiaLink
  }
}
</script>
