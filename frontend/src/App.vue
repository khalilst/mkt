<template>
  <div>
    <Header />

    <div class="container mt-4">
      <router-view />
    </div>
  </div>
</template>

<script setup lang="ts">
import Header from './components/Header.vue';

</script>

<style>
body {
  background: #f8f9fa;
}
</style>
