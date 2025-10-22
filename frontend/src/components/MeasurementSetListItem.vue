<!-- src/components/MeasurementSetTable.vue -->
<template>
  <td>
    <router-link class="link-primary"
      :to="{ name: pathKeys.measurementSetShow.name, params: { id: measurementSet.id } }">
      {{ measurementSet.id }}
    </router-link>
  </td>
  <td>
    <router-link class="link-primary"
      :to="{ name: pathKeys.measurementSetShow.name, params: { id: measurementSet.id } }">
      {{ measurementSet.title }}
    </router-link>
  </td>
  <td>{{ measurementSet.mkt ?? 'Not Calculated!' }}</td>
  <td>{{ new Date(measurementSet.created_at).toLocaleString() }}</td>
</template>

<script setup lang="ts">
import { pathKeys } from '@/shared/routes';
import type { MeasurementSet } from '@/types/mkt'

defineProps<{
  measurementSet: MeasurementSet,
}>()

</script>
