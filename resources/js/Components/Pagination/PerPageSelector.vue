<template>
  <div class="per-page-selector">
    <label for="per-page-select" class="mr-2">
      {{ translations?.pagination?.per_page || 'Items per page:' }}
    </label>
    <select
      id="per-page-select"
      v-model="localValue"
      @change="onChange"
      class="per-page-select"
    >
      <option v-for="option in options" :key="option" :value="option">
        {{ option }}
      </option>
    </select>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
  value: {
    type: Number,
    required: true
  },
  options: {
    type: Array,
    default: () => [10, 25, 50, 100]
  },
  translations: {
    type: Object,
    default: () => ({})
  }
});

const emit = defineEmits(['update']);

const localValue = ref(props.value);

watch(() => props.value, (newValue) => {
  localValue.value = newValue;
});

function onChange() {
  emit('update', localValue.value);
}
</script> 