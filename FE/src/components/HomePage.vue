<!-- App.vue -->
<template>
  <div class="home-page-wrapper">
    <SideBar />
    <ListView />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import SideBar from '../components/common/SideBar.vue'
import ListView from '../components/common/ListView.vue'
import { useProvideGalleryList } from '../store'
// Reactive state
const title = ref('Vue 3 Composition API Skeleton')
useProvideGalleryList()
</script>

<style scoped>
.home-page-wrapper {
  font-family: Arial, sans-serif;
  display: grid;
  grid-template-columns: 15% 85%;
  gap: 20px;
}
</style>
