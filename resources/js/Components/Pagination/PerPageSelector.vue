<template>
  <div class="per-page-selector">
    <label for="per-page-select" class="mr-2">
      {{ translations.pagination.per_page }}
    </label>
    <select
      id="per-page-select"
      v-model="model"
      class="per-page-select"
    >
      <option v-for="option in options" :key="option" :value="option">
        {{ option }}
      </option>
    </select>
  </div>
</template>

<script setup>
defineProps({
  options: {
    type: Array,
    default: () => [10, 25, 50, 100]
  },
  translations: {
    type: Object,
    required: true
  }
});

const model = defineModel();
</script> 