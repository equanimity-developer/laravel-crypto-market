<template>
  <thead class="bg-gray-50">
    <tr>
      <th scope="col" @click="toggleSort('market_cap_rank')" class="table-header">
        <div class="table-header-content">
          {{ translations.table.rank }}
          <SortIndicator :active="sortField === 'market_cap_rank'" :direction="sortDirection" />
        </div>
      </th>
      <th scope="col" @click="toggleSort('name')" class="table-header">
        <div class="table-header-content">
          {{ translations.table.name }}
          <SortIndicator :active="sortField === 'name'" :direction="sortDirection" />
        </div>
      </th>
      <th scope="col" @click="toggleSort('current_price')" class="table-header">
        <div class="table-header-content">
          {{ translations.table.price }}
          <SortIndicator :active="sortField === 'current_price'" :direction="sortDirection" />
        </div>
      </th>
      <th scope="col" @click="toggleSort('price_change_percentage_24h')" class="table-header">
        <div class="table-header-content">
          {{ translations.table.price_change_24h }}
          <SortIndicator :active="sortField === 'price_change_percentage_24h'" :direction="sortDirection" />
        </div>
      </th>
      <th scope="col" @click="toggleSort('market_cap')" class="table-header">
        <div class="table-header-content">
          {{ translations.table.market_cap }}
          <SortIndicator :active="sortField === 'market_cap'" :direction="sortDirection" />
        </div>
      </th>
    </tr>
  </thead>
</template>

<script setup>
import SortIndicator from '@/Components/SortIndicator.vue';

defineProps({
  translations: Object,
});

const sortField = defineModel('sortField');
const sortDirection = defineModel('sortDirection');

function toggleSort(field) {
  if (sortField.value === field) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortField.value = field;
    sortDirection.value = 'asc';
  }
}
</script> 