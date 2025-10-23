<template>
  <div class="container py-5">
    <h3>Upload Measurement Set</h3>
    <form @submit.prevent="submit">
      <div class="mb-3">
        <label class="form-label">Title</label>
        <input v-model="form.title" type="text" class="form-control" required />
      </div>
      <div class="mb-3">
        <label class="form-label">File</label>
        <input @change="selectFile" type="file" accept=".csv" class="form-control" required />
      </div>
      <button class="btn btn-primary" :class="{ disabled: loading }" :disabled="loading">Upload</button>
    </form>
  </div>
</template>

<script setup lang="ts">
import useMeasurementSetUploadForm from '@/composables/useMeasurementSetUploadForm';

const { selectFile, submit, loading, form } = useMeasurementSetUploadForm();

</script>
