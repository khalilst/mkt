<template>
  <PaginatedList :meta="paginationMeta" :prev="prev" :next="next" :loading="loading" :itemsCount="measurements.length">
    <MeasurementList :measurements="measurements" />
  </PaginatedList>
</template>

<script setup lang="ts">
import useMeasurements from '@/composables/useMeasurements';
import type { MeasurementSet } from '@/types/mkt'
import PaginatedList from './PaginatedList.vue';
import MeasurementList from './MeasurementList.vue';

const { measurementSet } = defineProps<{
  measurementSet: MeasurementSet
}>();

const {
  measurements,
  paginationMeta,
  loading,
  next,
  prev,
} = useMeasurements(measurementSet);

</script>
