<template>
  <div>
    <div class="flex space-x-2">
      <a
        href="/language/en"
        class="language-btn"
        :class="{'language-btn-active': currentLocale === 'en', 'language-btn-inactive': currentLocale !== 'en'}"
      >
        EN
      </a>
      <a
        href="/language/pl"
        class="language-btn"
        :class="{'language-btn-active': currentLocale === 'pl', 'language-btn-inactive': currentLocale !== 'pl'}"
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
