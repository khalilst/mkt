<template>
  <div class="mt-5">
    <Line :data="chartData" :options="chartOptions" />
  </div>
</template>

<script setup lang="ts">
import { Line } from 'vue-chartjs'
import {
  Chart as ChartJS,
  LineElement,
  PointElement,
  CategoryScale,
  LinearScale,
  Tooltip,
  Legend,
} from 'chart.js'
import { computed } from 'vue';
import type { Measurement } from '@/types/mkt';
import { useFormatDate } from '@/composables/useFormatDate';

ChartJS.register(LineElement, PointElement, CategoryScale, LinearScale, Tooltip, Legend)

const { formatDate } = useFormatDate();

const props = defineProps<{ measurements: Measurement[] }>()

const chartData = computed(() => ({
  labels: props.measurements.map(m => formatDate(m.measured_at)),
  datasets: [
    {
      label: 'Temperature (°C)',
      data: props.measurements.map(m => m.temperature),
      borderWidth: 2,
      fill: false,
      borderColor: '#36A2EB',
      tension: 0.1,
    },
  ],
}))

const chartOptions = {
  responsive: true,
  plugins: { legend: { position: 'bottom' } },
}
</script>
