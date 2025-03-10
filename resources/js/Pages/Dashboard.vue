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
          :applied-filters="filters"
          :translations="translations"
          @filter="handleFilter"
        />

        <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative mb-4">
          {{ error }}
        </div>

        <CryptoTable
          :cryptos="paginatedCryptos"
          :translations="translations"
          :sort-field="sortField"
          :sort-direction="sortDirection"
          @update:sort="handleSortUpdate"
        />

        <Pagination
          :current-page="currentPage"
          :items-per-page="itemsPerPage"
          :total-items="filteredCryptos.length"
          :translations="translations"
          @page-change="handlePageChange"
          @per-page-change="handlePerPageChange"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
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

const currentPage = ref(1);
const itemsPerPage = ref(25);
const sortField = ref('market_cap_rank');
const sortDirection = ref('asc');
const localFilters = ref({});

const filteredCryptos = computed(() => {
  return filterAndSortCryptos(cryptos, localFilters.value, sortField.value, sortDirection.value);
});

const paginatedCryptos = computed(() => {
  return getPaginatedData(filteredCryptos.value, currentPage.value, itemsPerPage.value);
});

function handlePageChange(newPage) {
  currentPage.value = newPage;
  document.querySelector('.overflow-x-auto')?.scrollIntoView({ behavior: 'smooth' });
}

function handleFilter(filters) {
  localFilters.value = filters;
  currentPage.value = 1;
}

function handleSortUpdate({ field, direction }) {
  sortField.value = field;
  sortDirection.value = direction;
  currentPage.value = 1;
}

function handlePerPageChange(newPerPage) {
  itemsPerPage.value = newPerPage;
  currentPage.value = 1;
  document.querySelector('.overflow-x-auto')?.scrollIntoView({ behavior: 'smooth' });
}
</script>
