import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

export function useTranslations() {
  const page = usePage();
  const translations = computed(() => page.props.translations);
  
  return {
    translations
  };
} 