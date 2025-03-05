<template>
  <div class="flex items-center justify-between border-t border-gray-200 px-4 py-3 sm:px-6 mt-4">
    <div class="flex flex-1 justify-between sm:hidden">
      <button
        @click="emit('page-change', currentPage - 1)"
        :disabled="currentPage <= 1"
        :class="currentPage <= 1 ? 'cursor-not-allowed opacity-50' : 'cursor-pointer hover:bg-gray-50'"
        class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700"
      >
        {{ translations?.pagination?.previous || 'Previous' }}
      </button>
      <button
        @click="emit('page-change', currentPage + 1)"
        :disabled="currentPage >= totalPages"
        :class="currentPage >= totalPages ? 'cursor-not-allowed opacity-50' : 'cursor-pointer hover:bg-gray-50'"
        class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700"
      >
        {{ translations?.pagination?.next || 'Next' }}
      </button>
    </div>
    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
      <div>
        <p class="text-sm text-gray-700">
          {{ translations?.pagination?.showing || 'Showing' }}
          <span class="font-medium">{{ startItem }}</span>
          {{ translations?.pagination?.to || 'to' }}
          <span class="font-medium">{{ endItem }}</span>
          {{ translations?.pagination?.of || 'of' }}
          <span class="font-medium">{{ totalItems }}</span>
          {{ translations?.pagination?.results || 'results' }}
        </p>
      </div>
      <div>
        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
          <button
            @click="emit('page-change', currentPage - 1)"
            :disabled="currentPage <= 1"
            :class="currentPage <= 1 ? 'cursor-not-allowed opacity-50' : 'cursor-pointer hover:bg-gray-50'"
            class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 focus:z-20 focus:outline-offset-0"
          >
            <span class="sr-only">Previous</span>
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
            </svg>
          </button>

          <template v-for="(page, index) in paginationRange" :key="index">
            <button
              v-if="page !== '...'"
              @click="emit('page-change', page)"
              class="cursor-pointer"
              :class="[
                page === currentPage
                  ? 'relative z-10 inline-flex items-center bg-indigo-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600'
                  : 'relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0'
              ]"
            >
              {{ page }}
            </button>
            <span
              v-else
              class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 focus:outline-offset-0"
            >
              ...
            </span>
          </template>

          <button
            @click="emit('page-change', currentPage + 1)"
            :disabled="currentPage >= totalPages"
            :class="currentPage >= totalPages ? 'cursor-not-allowed opacity-50' : 'cursor-pointer hover:bg-gray-50'"
            class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 focus:z-20 focus:outline-offset-0"
          >
            <span class="sr-only">Next</span>
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
            </svg>
          </button>
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const translations = computed(() => page.props.translations);

const props = defineProps({
  currentPage: {
    type: Number,
    required: true
  },
  itemsPerPage: {
    type: Number,
    required: true
  },
  totalItems: {
    type: Number,
    required: true
  }
});

const emit = defineEmits(['page-change']);

const totalPages = computed(() => Math.max(1, Math.ceil(props.totalItems / props.itemsPerPage)));
const startItem = computed(() => props.totalItems === 0 ? 0 : ((props.currentPage - 1) * props.itemsPerPage) + 1);
const endItem = computed(() => Math.min(startItem.value + props.itemsPerPage - 1, props.totalItems));

const paginationRange = computed(() => {
  const delta = 1;
  const range = [];
  let l;

  range.push(1);

  if (totalPages.value <= 1) {
    return range;
  }

  for (let i = 2; i < totalPages.value; i++) {
    if (i <= 1 + delta ||
        i >= totalPages.value - delta ||
        (i >= props.currentPage - delta && i <= props.currentPage + delta)) {
      range.push(i);
    } else if (i === props.currentPage - delta - 1 || i === props.currentPage + delta + 1) {
      range.push('...');
    }
  }

  if (totalPages.value > 1) {
    range.push(totalPages.value);
  }

  const rangeWithDots = [];
  for (let i of range) {
    if (l) {
      if (i === '...') {
        if (rangeWithDots[rangeWithDots.length - 1] !== '...') {
          rangeWithDots.push('...');
        }
      } else {
        rangeWithDots.push(i);
      }
    } else {
      rangeWithDots.push(i);
    }
    l = i;
  }

  return rangeWithDots;
});
</script>
