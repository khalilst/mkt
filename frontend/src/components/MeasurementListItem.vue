<template>
  <td>
      {{ formatDate(measurement.measured_at) }}
  </td>
  <td>
      {{ measurement.temperature }}
  </td>
</template>

<script setup lang="ts">
import { useFormatDate } from '@/composables/useFormatDate';
import type { Measurement } from '@/types/mkt'

const { formatDate } = useFormatDate();

defineProps<{
  measurement: Measurement,
}>()

</script>
