<template>
  <div>
    <label :for="id" class="filter-label">
      {{ label }}
    </label>
    <input
      :id="id"
      :type="type"
      v-model="inputValue"
      :placeholder="placeholder"
      class="filter-input"
    />
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';

const props = defineProps({
  id: {
    type: String,
    required: true
  },
  label: {
    type: String,
    required: true
  },
  type: {
    type: String,
    default: 'text'
  },
  placeholder: {
    type: String,
    default: ''
  },
  modelValue: {
    type: [String, Number],
    default: ''
  }
});

const emit = defineEmits(['update:modelValue']);

const inputValue = ref(props.modelValue);

watch(() => props.modelValue, (newValue) => {
  inputValue.value = newValue;
});

watch(inputValue, (newValue) => {
  emit('update:modelValue', newValue);
});

onMounted(() => {
  inputValue.value = props.modelValue;
});
</script> 