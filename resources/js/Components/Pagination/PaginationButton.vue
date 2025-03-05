<template>
  <button
    @click="$emit('click')"
    :disabled="disabled"
    :class="[
      baseClass,
      disabled ? 'pagination-disabled' : '',
      active ? activeClass : inactiveClass,
      ...extraClasses
    ]"
    class="cursor-pointer"
  >
    <slot></slot>
  </button>
</template>

<script setup>
defineProps({
  disabled: {
    type: Boolean,
    default: false
  },
  active: {
    type: Boolean,
    default: false
  },
  baseClass: {
    type: String,
    default: 'pagination-btn'
  },
  activeClass: {
    type: String,
    default: 'pagination-btn-active'
  },
  inactiveClass: {
    type: String,
    default: 'pagination-btn-inactive'
  },
  extraClasses: {
    type: Array,
    default: () => []
  }
});

defineEmits(['click']);
</script> 