<template>
  <div class="bg-white p-4 rounded-lg shadow-sm mb-6 border border-gray-200">
    <h3 class="text-lg font-medium text-gray-700 mb-4">{{ translations?.filters?.title || 'Filters' }}</h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
          {{ translations?.filters?.search || 'Search' }}
        </label>
        <input
          id="search"
          type="text"
          v-model="filterForm.search"
          :placeholder="translations?.filters?.search_placeholder || 'Bitcoin, ETH...'"
          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        />
      </div>

      <div>
        <label for="min_price" class="block text-sm font-medium text-gray-700 mb-1">
          {{ translations?.filters?.min_price || 'Min Price ($)' }}
        </label>
        <input
          id="min_price"
          type="number"
          v-model="filterForm.min_price"
          :placeholder="translations?.filters?.min_placeholder || '0'"
          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        />
      </div>

      <div>
        <label for="max_price" class="block text-sm font-medium text-gray-700 mb-1">
          {{ translations?.filters?.max_price || 'Max Price ($)' }}
        </label>
        <input
          id="max_price"
          type="number"
          v-model="filterForm.max_price"
          :placeholder="translations?.filters?.max_placeholder || '1000'"
          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        />
      </div>
    </div>

    <div class="flex justify-end mt-4 space-x-2">
      <button
        type="button"
        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        @click="resetFilters"
      >
        {{ translations?.filters?.reset || 'Reset' }}
      </button>
      <button
        type="button"
        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
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
