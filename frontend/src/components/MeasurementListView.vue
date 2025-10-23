<template>
  <div class="row">
    <PaginatedList class="col-md-6" :meta="paginationMeta" :prev="prev" :next="next" :loading="loading"
      :itemsCount="measurements.length">
      <MeasurementList :measurements="measurements" />
    </PaginatedList>

    <MeasurementChart class="col-md-6" :measurements="measurements" />
  </div>
</template>

<script setup lang="ts">
import useMeasurements from '@/composables/useMeasurements';
import type { MeasurementSet } from '@/types/mkt'
import PaginatedList from './PaginatedList.vue';
import MeasurementList from './MeasurementList.vue';
import MeasurementChart from './MeasurementChart.vue';

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
