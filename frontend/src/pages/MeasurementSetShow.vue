<template>
    <div class="container py-4">
        <h3>Measurement Set</h3>
        <div v-if="set" class="d-flex justify-content-between align-items-center mb-3">
            <p class="fs-4">{{ set.title }}</p>
            <p class="text-success">
                <small>Mkt: </small>
                <span :class="{ 'spinner-border spinner-border-sm': !set.mkt }">{{ set.mkt }}</span>
            </p>
            <p><small>Date: </small>{{ formatDate(set.created_at) }}</p>
        </div>
        <div v-else class="spinner-border text-primary"></div>

        <MeasurementListView v-if="set && set.mkt !== null" :measurementSet="set" />
    </div>
</template>

<script setup lang="ts">
import { useRoute } from 'vue-router';
import { useFormatDate } from '@/composables/useFormatDate';
import MeasurementListView from '@/components/MeasurementListView.vue';
import useMeasurementSet from '@/composables/useMeasurementSet';

const route = useRoute();

const { formatDate } = useFormatDate();

const { set } = useMeasurementSet(Number(route.params.id));

</script>
