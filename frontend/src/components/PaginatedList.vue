<template>
  <div>
    <Pagination :meta="meta" @next="next" @prev="prev" />

    <slot v-if="itemsCount"></slot>

    <Pagination :meta="meta" @next="next" @prev="prev" />

    <div v-if="!loading && !itemsCount" class="text-muted text-center my-4">
      No items found!
    </div>

    <div v-if="loading" class="text-center mt-3">
      <div class="spinner-border text-primary"></div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { PaginationMeta } from '@/types/pagination';
import Pagination from './Pagination.vue';


const { meta, next, prev, loading, itemsCount } = defineProps<{
  meta: PaginationMeta,
  next: () => void,
  prev: () => void,
  loading: boolean,
  itemsCount: number,
}>();

</script>
