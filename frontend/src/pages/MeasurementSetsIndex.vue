<template>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Measurement Sets</h3>
            <router-link to="/upload" class="btn btn-primary btn-sm">Upload New Measurement Set</router-link>
        </div>

        <Pagination :meta="paginationMeta" @next="next" @prev="prev" />

        <MeasurementSetList v-if="measurementSets.length" :measurementSets="measurementSets" />

        <Pagination :meta="paginationMeta" @next="next" @prev="prev"/>

        <div v-if="!loading && !measurementSets.length" class="text-muted text-center my-4">
            No sets found.
        </div>

        <div v-if="loading" class="text-center mt-3">
            <div class="spinner-border text-primary"></div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import MeasurementSetList from '@/components/MeasurementSetList.vue'
import { useMeasurementSets } from '@/composables/useMeasurementSet'
import Pagination from '@/components/Pagination.vue'

const {
    measurementSets,
    paginationMeta,
    loading,
    fetchSets,
    next,
    prev,
} = useMeasurementSets()

onMounted(fetchSets)

</script>
