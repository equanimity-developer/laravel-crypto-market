<template>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-bold text-gray-800">{{ translations.title }}</h1>
          <LanguageSwitcher />
        </div>

        <!-- Filter component -->
        <CryptoFilter
          :applied-filters="filters"
          :translations="translations"
          @filter="handleFilter"
        />

        <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative mb-4">
          {{ error }}
        </div>

        <div v-else-if="!cryptos || cryptos.length === 0" class="flex justify-center items-center h-40">
          <div class="text-gray-500">{{ translations.loading }}</div>
        </div>

        <div v-else class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col"
                    @click="toggleSort('market_cap_rank')"
                    class="table-header cursor-pointer">
                  <div class="flex items-center">
                    {{ translations.table.rank }}
                    <SortIndicator :active="sortField === 'market_cap_rank'" :direction="sortDirection" />
                  </div>
                </th>
                <th scope="col"
                    @click="toggleSort('name')"
                    class="table-header cursor-pointer">
                  <div class="flex items-center">
                    {{ translations.table.name }}
                    <SortIndicator :active="sortField === 'name'" :direction="sortDirection" />
                  </div>
                </th>
                <th scope="col"
                    @click="toggleSort('current_price')"
                    class="table-header cursor-pointer">
                  <div class="flex items-center">
                    {{ translations.table.price }}
                    <SortIndicator :active="sortField === 'current_price'" :direction="sortDirection" />
                  </div>
                </th>
                <th scope="col"
                    @click="toggleSort('price_change_percentage_24h')"
                    class="table-header cursor-pointer">
                  <div class="flex items-center">
                    {{ translations.table.change_24h }}
                    <SortIndicator :active="sortField === 'price_change_percentage_24h'" :direction="sortDirection" />
                  </div>
                </th>
                <th scope="col"
                    @click="toggleSort('market_cap')"
                    class="table-header cursor-pointer">
                  <div class="flex items-center">
                    {{ translations.table.market_cap }}
                    <SortIndicator :active="sortField === 'market_cap'" :direction="sortDirection" />
                  </div>
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="crypto in sortedAndFilteredCryptos" :key="crypto.id" class="hover:bg-gray-50">
                <td class="table-cell">
                  {{ crypto.market_cap_rank }}
                </td>
                <td class="table-cell-with-name">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                      <img class="h-10 w-10 rounded-full" :src="crypto.image" :alt="crypto.name">
                    </div>
                    <div class="ml-4">
                      <div class="text-name">
                        {{ crypto.name }}
                      </div>
                      <div class="text-symbol">
                        {{ crypto.symbol.toUpperCase() }}
                      </div>
                    </div>
                  </div>
                </td>
                <td class="table-price-cell">
                  <div class="text-sm text-gray-900">${{ formatPrice(crypto.current_price) }}</div>
                </td>
                <td class="table-cell">
                  <span :class="getPriceChangeClass(crypto.price_change_percentage_24h)">
                    {{ formatPercentage(crypto.price_change_percentage_24h) }}
                  </span>
                </td>
                <td class="table-cell">
                  ${{ formatMarketCap(crypto.market_cap) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';
import CryptoFilter from '@/Components/CryptoFilter.vue';
import SortIndicator from '@/Components/SortIndicator.vue';

const page = usePage();
const translations = computed(() => page.props.translations);

const props = defineProps({
  cryptos: Array,
  filters: Object,
  error: String,
});

const localFilters = ref({
  search: props.filters?.search || '',
  min_price: props.filters?.min_price || '',
  max_price: props.filters?.max_price || ''
});

const sortField = ref('market_cap_rank');
const sortDirection = ref('asc');

const filteredCryptos = computed(() => {
  if (!props.cryptos) return [];
  let result = [...props.cryptos];

  if (localFilters.value.search) {
    const searchTerm = localFilters.value.search.toLowerCase();
    result = result.filter(crypto =>
      crypto.name.toLowerCase().includes(searchTerm) ||
      crypto.symbol.toLowerCase().includes(searchTerm)
    );
  }

  if (localFilters.value.min_price) {
    const minPrice = parseFloat(localFilters.value.min_price);
    if (!isNaN(minPrice)) {
      result = result.filter(crypto => crypto.current_price >= minPrice);
    }
  }

  if (localFilters.value.max_price) {
    const maxPrice = parseFloat(localFilters.value.max_price);
    if (!isNaN(maxPrice)) {
      result = result.filter(crypto => crypto.current_price <= maxPrice);
    }
  }

  return result;
});

const sortedAndFilteredCryptos = computed(() => {
  const result = [...filteredCryptos.value];

  return result.sort((a, b) => {
    const aValue = a[sortField.value];
    const bValue = b[sortField.value];

    if (aValue == null && bValue == null) return 0;
    if (aValue == null) return 1;
    if (bValue == null) return -1;

    const modifier = sortDirection.value === 'asc' ? 1 : -1;

    if (typeof aValue === 'string') {
      return aValue.localeCompare(bValue) * modifier;
    } else {
      return (aValue - bValue) * modifier;
    }
  });
});

function toggleSort(field) {
  if (sortField.value === field) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortField.value = field;
    sortDirection.value = 'asc';
  }
}

function handleFilter(filters) {
  localFilters.value = filters;
}

function formatPrice(price) {
  if (price >= 1) {
    return price.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  } else {
    return price.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 6 });
  }
}

function formatPercentage(percentage) {
  if (!percentage) return '0.00%';
  return percentage.toFixed(2) + '%';
}

function formatMarketCap(marketCap) {
  if (marketCap >= 1e9) {
    return (marketCap / 1e9).toFixed(2) + 'B';
  } else if (marketCap >= 1e6) {
    return (marketCap / 1e6).toFixed(2) + 'M';
  } else {
    return marketCap.toLocaleString('en-US');
  }
}

function getPriceChangeClass(change) {
  if (!change) return 'text-gray-500';
  return change >= 0 ? 'text-green-600' : 'text-red-600';
}
</script>
