<template>
  <div class="storage-wrapper">
    <div class="d-flex justify-space-between storage-header">
      <p class="title text-subtitle-2 text-medium-emphasis">Storage</p>
      <span>
        <a class="text-decoration-underline change-plan text-subtitle-2 text-medium-emphasis">
          Change plan</a
        >
      </span>
    </div>
    <div class="storage-footer mt-2">
      <v-progress-linear
        class="progress-bar"
        color="blue-darken-2"
        :model-value="percentageUsed"
        :height="12"
      ></v-progress-linear>
      <span>
        <span class="percentage-used font-weight-medium">{{ percentageUsed }}%</span> used of
        2GB</span
      >
    </div>
  </div>
</template>
<script>
import { defineComponent, ref } from 'vue'
import { useGalleryList } from '@/store'
export default defineComponent({
  components: {},
  setup() {
    const { currentUsage } = useGalleryList()

    const percentageUsed = ref(currentUsage.value)
    return {
      percentageUsed,
    }
  },
})
</script>
<style lang="sass">
.percentage-used
    color: #1a73e8
.progress-bar
    border-radius: 50px
</style>
