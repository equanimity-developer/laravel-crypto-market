<template>
  <div class="filter-container">
    <h3 class="filter-title">{{ translations.filters.title }}</h3>

    <div class="filter-grid">
      <FilterInput
        id="search"
        :label="translations.filters.search"
        type="text"
        :placeholder="translations.filters.search_placeholder"
        v-model="filterForm.search"
      />

      <FilterInput
        id="min_price"
        :label="translations.filters.min_price"
        type="number"
        :placeholder="translations.filters.min_placeholder"
        v-model="filterForm.min_price"
      />

      <FilterInput
        id="max_price"
        :label="translations.filters.max_price"
        type="number"
        :placeholder="translations.filters.max_placeholder"
        v-model="filterForm.max_price"
      />
    </div>

    <div class="filter-actions">
      <button
        type="button"
        class="btn-secondary"
        @click="resetFilters"
      >
        {{ translations.filters.reset }}
      </button>
      <button
        type="button"
        class="btn-primary"
        @click="applyFilters"
      >
        {{ translations.filters.apply }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useTranslations } from '@/Composables/useTranslations';
import FilterInput from '@/Components/CryptoFilter/FilterInput.vue';

const page = usePage();
const { translations } = useTranslations();

const filters = defineModel();

const filterForm = ref({
  search: '',
  min_price: '',
  max_price: ''
});

onMounted(() => {
  if (filters.value) {
    filterForm.value = {
      search: filters.value.search || '',
      min_price: filters.value.min_price || '',
      max_price: filters.value.max_price || ''
    };
  }
});

function applyFilters() {
  filters.value = { ...filterForm.value };
}

function resetFilters() {
  filterForm.value = {
    search: '',
    min_price: '',
    max_price: ''
  };
  filters.value = {};
}
</script>
