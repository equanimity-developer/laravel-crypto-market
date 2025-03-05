<template>
  <span class="ml-1 flex-none">
    <Icon 
      :name="iconName" 
      :class="iconClass"
    />
  </span>
</template>

<script setup>
import { computed } from 'vue';
import Icon from '@/Components/UI/Icon.vue';

const props = defineProps({
  active: {
    type: Boolean,
    default: false
  },
  direction: {
    type: String,
    default: 'asc'
  }
});

const iconName = computed(() => {
  if (!props.active) {
    return 'sort-neutral';
  }
  return props.direction === 'asc' ? 'sort-asc' : 'sort-desc';
});

const iconClass = computed(() => {
  return props.active ? 'text-indigo-600' : 'text-gray-400';
});
</script> 