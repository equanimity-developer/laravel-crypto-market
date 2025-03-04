<template>
  <div>
    <div class="flex space-x-2">
      <a
        href="/language/en"
        class="px-2 py-1 text-sm rounded"
        :class="{'bg-blue-600 text-white': currentLocale === 'en', 'bg-gray-200 hover:bg-gray-300': currentLocale !== 'en'}"
      >
        EN
      </a>
      <a
        href="/language/pl"
        class="px-2 py-1 text-sm rounded"
        :class="{'bg-blue-600 text-white': currentLocale === 'pl', 'bg-gray-200 hover:bg-gray-300': currentLocale !== 'pl'}"
      >
        PL
      </a>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const currentLocale = computed(() => {
  return page.props.app?.locale || 'en';
});
</script>
