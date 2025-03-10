<template>
  <div>
    <div v-if="!cryptos || cryptos.length === 0" class="flex justify-center items-center h-40">
      <div class="text-gray-500">{{ translations.loading }}</div>
    </div>
    <div v-else>
      <div class="overflow-x-auto">
        <table class="crypto-table">
          <TableHeader
            :translations="translations"
            v-model:sort.field="sortField"
            v-model:sort.direction="sortDirection"
          />
          <tbody class="bg-white divide-y divide-gray-200">
            <TableRow
              v-for="crypto in cryptos"
              :key="crypto.id"
              :crypto="crypto"
            />
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import TableHeader from './TableHeader.vue';
import TableRow from './TableRow.vue';

defineProps({
  cryptos: Array,
  translations: Object,
});

const sortField = defineModel('sortField');
const sortDirection = defineModel('sortDirection');
</script> 