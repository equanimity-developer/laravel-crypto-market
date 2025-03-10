<template>
  <div class="text-sm text-gray-700">
    {{ translations.pagination.showing }}
    <span class="font-medium">{{ startItem }}</span>
    {{ translations.pagination.to }}
    <span class="font-medium">{{ endItem }}</span>
    {{ translations.pagination.of }}
    <span class="font-medium">{{ totalItems }}</span>
    {{ translations.pagination.results }}
  </div>
</template>

<script setup>
defineProps({
  startItem: {
    type: Number,
    required: true
  },
  endItem: {
    type: Number,
    required: true
  },
  totalItems: {
    type: Number,
    required: true
  },
  translations: {
    type: Object,
    required: true
  }
});
</script> 