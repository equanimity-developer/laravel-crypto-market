<template>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-bold text-gray-800">{{ translations.title }}</h1>
          <LanguageSwitcher />
        </div>
        
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
                <th scope="col" class="table-header">
                  {{ translations.table.rank }}
                </th>
                <th scope="col" class="table-header">
                  {{ translations.table.name }}
                </th>
                <th scope="col" class="table-header">
                  {{ translations.table.price }}
                </th>
                <th scope="col" class="table-header">
                  {{ translations.table.change_24h }}
                </th>
                <th scope="col" class="table-header">
                  {{ translations.table.market_cap }}
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="crypto in cryptos" :key="crypto.id" class="hover:bg-gray-50">
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
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';

defineProps({
  cryptos: Array,
  filters: Object,
  error: String,
  translations: Object
});

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