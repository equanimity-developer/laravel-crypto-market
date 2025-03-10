<template>
  <div>
    <div class="flex space-x-2 h-9">
      <a
        href="/language/en"
        class="language-btn shadow-sm inline-flex items-center justify-center px-4 py-2"
        :class="{'language-btn-active': currentLocale === 'en', 'language-btn-inactive': currentLocale !== 'en'}"
      >
        EN
      </a>
      <a
        href="/language/pl"
        class="language-btn shadow-sm inline-flex items-center justify-center px-4 py-2"
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
import { useTranslations } from '@/Composables/useTranslations';

const page = usePage();
const { translations } = useTranslations();
const currentLocale = computed(() => {
  return page.props.app?.locale || 'en';
});
</script>
