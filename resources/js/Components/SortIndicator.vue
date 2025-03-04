<template>
  <span class="ml-1 inline-flex flex-col">
    <svg v-if="active && direction === 'asc'" class="h-4 w-4 text-indigo-500" fill="currentColor" viewBox="0 0 24 24">
      <path d="M12 5l8 8H4z"></path>
    </svg>
    <svg v-else-if="active && direction === 'desc'" class="h-4 w-4 text-indigo-500" fill="currentColor" viewBox="0 0 24 24">
      <path d="M12 19l-8-8h16z"></path>
    </svg>
    <svg v-else class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
      <path d="M12 5l8 8H4z M12 19l-8-8h16z" fill-opacity="0.3"></path>
    </svg>
  </span>
</template>

<script setup>
defineProps({
  active: {
    type: Boolean,
    default: false
  },
  direction: {
    type: String,
    default: 'asc'
  }
});
</script> 