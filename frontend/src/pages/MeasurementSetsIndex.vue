<template>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Measurement Sets</h3>
            <router-link to="/upload" class="btn btn-primary btn-sm">Upload New Measurement Set</router-link>
        </div>

        <PaginatedList
            :meta="paginationMeta"
            :prev="prev"
            :next="next"
            :loading="loading"
            :itemsCount="measurementSets.length"
        >
            <MeasurementSetList :measurementSets="measurementSets" />
        </PaginatedList>
    </div>
</template>

<script setup lang="ts">
import MeasurementSetList from '@/components/MeasurementSetList.vue'
import useMeasurementSets from '@/composables/useMeasurementSets'
import PaginatedList from '@/components/PaginatedList.vue';

const {
    measurementSets,
    paginationMeta,
    loading,
    next,
    prev,
} = useMeasurementSets();

</script>
