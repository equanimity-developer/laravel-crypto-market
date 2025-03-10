<template>
  <div class="pagination-container">
    <div class="pagination-mobile">
      <PaginationButton
        @click="currentPage = Math.max(1, currentPage - 1)"
        :disabled="currentPage <= 1"
        :base-class="'btn-secondary'"
        :inactive-class="''"
        :extra-classes="['mr-3']"
      >
        {{ translations.pagination.previous }}
      </PaginationButton>
      <PaginationButton
        @click="currentPage = Math.min(totalPages, currentPage + 1)"
        :disabled="currentPage >= totalPages"
        :base-class="'btn-secondary'"
        :inactive-class="''"
      >
        {{ translations.pagination.next }}
      </PaginationButton>
    </div>
    <div class="pagination-desktop">
      <div class="flex items-center gap-x-6">
        <PaginationInfo
          :start-item="startItem"
          :end-item="endItem"
          :total-items="totalItems"
          :translations="translations"
        />
        <PerPageSelector
          v-model="itemsPerPage"
          :translations="translations"
        />
      </div>
      <div>
        <nav class="pagination-nav" aria-label="Pagination">
          <PaginationButton
            @click="currentPage = Math.max(1, currentPage - 1)"
            :disabled="currentPage <= 1"
            :base-class="'pagination-arrow'"
            :inactive-class="''"
            :extra-classes="['rounded-l-md']"
          >
            <span class="sr-only">Previous</span>
            <Icon name="chevron-left" />
          </PaginationButton>

          <template v-for="(page, index) in paginationRange" :key="index">
            <PaginationButton
              v-if="page !== '...'"
              @click="currentPage = page"
              :active="page === currentPage"
            >
              {{ page }}
            </PaginationButton>
            <span
              v-else
              class="pagination-btn"
            >
              ...
            </span>
          </template>

          <PaginationButton
            @click="currentPage = Math.min(totalPages, currentPage + 1)"
            :disabled="currentPage >= totalPages"
            :base-class="'pagination-arrow'"
            :inactive-class="''"
            :extra-classes="['rounded-r-md']"
          >
            <span class="sr-only">Next</span>
            <Icon name="chevron-right" />
          </PaginationButton>
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useTranslations } from '@/Composables/useTranslations';
import PaginationButton from '@/Components/Pagination/PaginationButton.vue';
import PaginationInfo from '@/Components/Pagination/PaginationInfo.vue';
import PerPageSelector from '@/Components/Pagination/PerPageSelector.vue';
import Icon from '@/Components/UI/Icon.vue';

const { translations } = useTranslations();

const props = defineProps({
  totalItems: {
    type: Number,
    required: true
  }
});

const currentPage = defineModel('currentPage');
const itemsPerPage = defineModel('itemsPerPage');

const totalPages = computed(() => Math.max(1, Math.ceil(props.totalItems / itemsPerPage.value)));
const startItem = computed(() => props.totalItems === 0 ? 0 : ((currentPage.value - 1) * itemsPerPage.value) + 1);
const endItem = computed(() => Math.min(startItem.value + itemsPerPage.value - 1, props.totalItems));

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
        (i >= currentPage.value - delta && i <= currentPage.value + delta)) {
      range.push(i);
    } else if (i === currentPage.value - delta - 1 || i === currentPage.value + delta + 1) {
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
