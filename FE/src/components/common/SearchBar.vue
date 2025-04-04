<template>
  <div class="search-wrapper">
    <div class="flex-column">
      <p class="title">Search</p>
      <v-text-field
        append-inner-icon="mdi-magnify"
        density="compact"
        placeholder="e.g.image.png"
        variant="outlined"
        v-model="searchString"
        hide-details
        single-line
      ></v-text-field>
    </div>
  </div>
</template>
<script>
import { defineComponent, ref } from 'vue'
import { useGalleryList } from '@/store'
export default defineComponent({
  components: {},
  setup() {
    const { searchString } = useGalleryList()
    return {
      searchString,
    }
  },
})
</script>
<style lang="sass"></style>
