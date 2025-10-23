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
  <td>{{ formatDate(measurementSet.created_at) }}</td>
</template>

<script setup lang="ts">
import { useFormatDate } from '@/composables/useFormatDate';
import { pathKeys } from '@/shared/routes';
import type { MeasurementSet } from '@/types/mkt'

const { formatDate } = useFormatDate();

defineProps<{
  measurementSet: MeasurementSet,
}>()

</script>
