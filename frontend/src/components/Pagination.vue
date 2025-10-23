<template>
  <div class="d-flex justify-content-between align-items-center">
    <button class="btn border-0 page-item fs-4" @click="emit('prev')" :class="{ disabled: prevDisabled }"
      :disabled="prevDisabled">
      &laquo;
    </button>

    <button class="btn border-0 page-item fs-4" @click="emit('next')" :class="{ disabled: nextDisabled }"
      :disabled="nextDisabled">
      &raquo;
    </button>
  </div>
</template>

<script setup lang="ts">
import type { PaginationMeta } from '@/types/pagination';
import { computed } from 'vue';


const { meta } = defineProps<{
  meta: PaginationMeta,
}>()

const emit = defineEmits(['next', 'prev']);
const prevDisabled = computed(() => !!meta.pages && meta.page === 1);
const nextDisabled = computed(() => !!meta.pages && meta.page === meta.pages);

</script>
