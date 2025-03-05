<template>
  <tr class="table-row">
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
</template>

<script setup>
import { formatPrice, formatPercentage, formatMarketCap, getPriceChangeClass } from '@/Utils/formatting.js';

defineProps({
  crypto: {
    type: Object,
    required: true
  }
});
</script> 