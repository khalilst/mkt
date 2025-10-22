<!-- src/components/MeasurementSetTable.vue -->
<template>
  <table class="table table-hover table-striped align-middle">
    <thead class="table-light">
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>MKT</th>
        <th>Created Date</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="measurementSet in measurementSets" :key="measurementSet.id">
        <MeasurementSetListItem :measurementSet="measurementSet" />
      </tr>
    </tbody>
  </table>
</template>

<script setup lang="ts">
import type { MeasurementSet } from '@/types/mkt'
import MeasurementSetListItem from './MeasurementSetListItem.vue';

defineProps<{
  measurementSets: MeasurementSet[]
}>()

</script>
