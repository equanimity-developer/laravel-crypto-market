<template>
  <p class="pagination-info">
    {{ translations?.pagination?.showing || 'Showing' }}
    <span class="pagination-info-highlight">{{ startItem }}</span>
    {{ translations?.pagination?.to || 'to' }}
    <span class="pagination-info-highlight">{{ endItem }}</span>
    {{ translations?.pagination?.of || 'of' }}
    <span class="pagination-info-highlight">{{ totalItems }}</span>
    {{ translations?.pagination?.results || 'results' }}
  </p>
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
    default: () => ({})
  }
});
</script> 