<template>
  <div>
    <nav class="navbar navbar-expand navbar-dark bg-dark px-3">
      <router-link class="navbar-brand" to="/">MKT Calculator</router-link>
      <div class="navbar-nav">
        <router-link class="nav-link" to="/">Measurement Sets</router-link>
        <router-link class="nav-link" to="/upload">Upload</router-link>
      </div>
    </nav>

    <div class="container mt-4">
      <router-view />
    </div>
  </div>
</template>

<script setup lang="ts"></script>

<style>
body {
  background: #f8f9fa;
}
</style>
