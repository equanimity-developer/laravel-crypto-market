<template>
  <button 
    :class="[
      'btn-secondary',
      'inline-flex items-center space-x-1'
    ]"
    @click="refreshMarketData"
    :disabled="isLoading"
  >
    <svg 
      v-if="isLoading" 
      class="animate-spin h-4 w-4" 
      xmlns="http://www.w3.org/2000/svg" 
      fill="none" 
      viewBox="0 0 24 24"
    >
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>
    <svg 
      v-else 
      class="h-4 w-4" 
      xmlns="http://www.w3.org/2000/svg" 
      fill="none" 
      viewBox="0 0 24 24" 
      stroke="currentColor"
    >
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
    </svg>
    <span>{{ isLoading ? translations.refresh.loading : translations.refresh.button }}</span>
  </button>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useTranslations } from '@/Composables/useTranslations';
import axios from 'axios';

const page = usePage();
const { translations } = useTranslations();
const isLoading = ref(false);

async function refreshMarketData() {
  isLoading.value = true;
  
  try {
    await axios.post('/refresh-market-data');
    window.location.reload();
  } catch (error) {
    console.error('Error refreshing data:', error);
    isLoading.value = false;
  }
}
</script> 