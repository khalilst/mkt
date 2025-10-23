<template>
    <div class="container py-4">
        <h3>Measurement Set</h3>
        <div v-if="set" class="d-flex justify-content-between align-items-center mb-3">
            <p class="fs-4">{{ set.title }}</p>
            <p class="text-success"><small>Mkt: </small>{{ set.mkt || 'Not Calculated!' }}</p>
            <p><small>Date: </small>{{ formatDate(set.created_at) }}</p>
        </div>
        <div v-else class="spinner-border text-primary"></div>

        <PaginatedMeasurementList v-if="set" :measurementSet="set" />
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { type MeasurementSet } from '@/types/mkt';
import api from '@/services/api';
import { useRoute } from 'vue-router';
import { endpoints } from '@/shared/endpoints';
import { useFormatDate } from '@/composables/useFormatDate';
import PaginatedMeasurementList from '@/components/PaginatedMeasurementList.vue';

const route = useRoute();
const id = Number(route.params.id);

const set = ref<MeasurementSet | null>(null);

const { formatDate } = useFormatDate();

const loadSet = async () => {
    const { data } = await api.get(endpoints.mkt.measurementSetShow(id));
    set.value = data;
}

onMounted(loadSet);

</script>
