<template>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-bold text-gray-800">{{ translations.title }}</h1>
          <div class="flex items-center space-x-2">
            <ReloadButton />
            <LanguageSwitcher />
          </div>
        </div>

        <CryptoFilter
          v-model="localFilters"
          :translations="translations"
        />

        <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative mb-4">
          {{ error }}
        </div>

        <CryptoTable
          ref="cryptoTableRef"
          :cryptos="paginatedCryptos"
          :translations="translations"
          v-model:sortField="sortField"
          v-model:sortDirection="sortDirection"
        />

        <Pagination
          v-model:currentPage="currentPage"
          v-model:itemsPerPage="itemsPerPage"
          :total-items="filteredCryptos.length"
          :translations="translations"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useTranslations } from '@/Composables/useTranslations';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';
import ReloadButton from '@/Components/UI/ReloadButton.vue';
import CryptoFilter from '@/Components/CryptoFilter.vue';
import CryptoTable from '@/Components/CryptoTable/CryptoTable.vue';
import Pagination from '@/Components/Pagination.vue';
import { filterAndSortCryptos, getPaginatedData } from '@/Utils/filtering.js';

const page = usePage();
const cryptos = page.props.cryptos || [];
const error = page.props.error;
const filters = page.props.filters || {};
const { translations } = useTranslations();

const cryptoTableRef = ref(null);
const currentPage = ref(1);
const itemsPerPage = ref(25);
const sortField = ref('market_cap_rank');
const sortDirection = ref('asc');
const localFilters = ref(filters);

const filteredCryptos = computed(() => {
  return filterAndSortCryptos(cryptos, localFilters.value, sortField.value, sortDirection.value);
});

const paginatedCryptos = computed(() => {
  return getPaginatedData(filteredCryptos.value, currentPage.value, itemsPerPage.value);
});

watch([sortField, sortDirection, localFilters, itemsPerPage], () => {
  currentPage.value = 1;
});

watch([currentPage, itemsPerPage], () => {
  scrollToTable();
});

function scrollToTable() {
  if (cryptoTableRef.value) {
    cryptoTableRef.value.$el.scrollIntoView({ behavior: 'smooth' });
  }
}
</script>
