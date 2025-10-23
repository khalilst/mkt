<template>
  <table class="table table-hover table-striped align-middle">
    <thead class="table-light">
      <tr>
        <th>Measurement Time</th>
        <th>Temperature °C</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="measurement in measurements" :key="measurement.id">
        <MeasurementListItem :measurement="measurement" />
      </tr>
    </tbody>
  </table>
</template>

<script setup lang="ts">
import type { Measurement } from '@/types/mkt'
import MeasurementListItem from './MeasurementListItem.vue';

defineProps<{
  measurements: Measurement[]
}>()

</script>
