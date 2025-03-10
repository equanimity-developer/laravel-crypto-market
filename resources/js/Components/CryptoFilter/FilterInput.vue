<template>
  <div>
    <label :for="id" class="filter-label">
      {{ label }}
    </label>
    <input
      :id="id"
      :type="type"
      v-model="model"
      :placeholder="placeholder"
      class="filter-input"
    />
  </div>
</template>

<script setup>
defineProps({
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
  }
});

const model = defineModel();
</script>
