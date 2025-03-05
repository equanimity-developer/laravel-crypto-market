<template>
  <div class="filter-container">
    <h3 class="filter-title">{{ translations?.filters?.title || 'Filters' }}</h3>

    <div class="filter-grid">
      <FilterInput
        id="search"
        :label="translations?.filters?.search || 'Search'"
        type="text"
        :placeholder="translations?.filters?.search_placeholder || 'Bitcoin, ETH...'"
        v-model="filterForm.search"
      />

      <FilterInput
        id="min_price"
        :label="translations?.filters?.min_price || 'Min Price ($)'"
        type="number"
        :placeholder="translations?.filters?.min_placeholder || '0'"
        v-model="filterForm.min_price"
      />

      <FilterInput
        id="max_price"
        :label="translations?.filters?.max_price || 'Max Price ($)'"
        type="number"
        :placeholder="translations?.filters?.max_placeholder || '1000'"
        v-model="filterForm.max_price"
      />
    </div>

    <div class="filter-actions">
      <button
        type="button"
        class="btn-secondary"
        @click="resetFilters"
      >
        {{ translations?.filters?.reset || 'Reset' }}
      </button>
      <button
        type="button"
        class="btn-primary"
        @click="applyFilters"
      >
        {{ translations?.filters?.apply || 'Apply' }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import FilterInput from '@/Components/CryptoFilter/FilterInput.vue';

const page = usePage();
const translations = computed(() => page.props.translations);

const props = defineProps({
  appliedFilters: Object,
});

const emit = defineEmits(['filter']);

const filterForm = ref({
  search: '',
  min_price: '',
  max_price: ''
});

onMounted(() => {
  if (props.appliedFilters) {
    filterForm.value = {
      search: props.appliedFilters.search || '',
      min_price: props.appliedFilters.min_price || '',
      max_price: props.appliedFilters.max_price || ''
    };
  }
});

function applyFilters() {
  emit('filter', filterForm.value);
}

function resetFilters() {
  filterForm.value = {
    search: '',
    min_price: '',
    max_price: ''
  };
  emit('filter', {});
}
</script>
