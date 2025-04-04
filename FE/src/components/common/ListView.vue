<template>
  <div class="image-gallery element-padding">
    <div class="gallery-header">
      <div class="checkbox-cell">
        <input type="checkbox" id="select-all" @change="toggleSelectAll" />
        <label for="select-all">Select all</label>
      </div>
      <div class="header-cell name-cell">Name <span class="sort-icon">↕</span></div>
      <div class="header-cell dimension-cell">Dimmension <span class="sort-icon">↕</span></div>
      <div class="header-cell size-cell">Size <span class="sort-icon">↕</span></div>
    </div>
    <div v-for="image in images" :key="image.id">
      <div v-if="isShown(image)" class="gallery-row">
        <div class="checkbox-cell">
          <v-checkbox
            v-model="selectedImages"
            :value="image.id"
            color="primary"
            hide-details
          ></v-checkbox>
        </div>
        <div class="image-name-cell">
          <div class="image-preview">
            <img :src="image.url" :alt="image.name" />
          </div>
          <div class="name">{{ image.name }}</div>
        </div>
        <div class="dimension-cell">{{ image.dimmension }}</div>
        <div class="size-cell">{{ formatSize(image.size) }}</div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, defineComponent, watch, computed } from 'vue'
import { useGalleryList } from '@/store'
export default defineComponent({
  setup() {
    const { currentImgList, currentRole } = useGalleryList()

    const images = computed(() => currentImgList.value)

    const selectedImages = ref([])
    const selectAll = ref(false)

    const formatSize = (bytes) => {
      const kb = Math.round(parseInt(bytes) / 1000)
      return `${kb.toLocaleString()} kB`
    }

    const toggleSelectAll = () => {
      selectAll.value = !selectAll.value
      if (selectAll.value) {
        selectedImages.value = images.value.map((img) => img.id)
      } else {
        selectedImages.value = []
      }
    }

    const handleCheckRow = () => {
      return true
    }

    // Watch for changes in selectedImages to update selectAll state
    watch(selectedImages, (newVal) => {
      selectAll.value = newVal.length === images.value.length
    })

    const isShown = (image) => {
      if (image.photo_by !== 'Admin' && currentRole.value.some((item) => item === 'All'))
        return true
      if (image.photo_by === 'Admin' && currentRole.value.some((item) => item === 'Admin'))
        return true
      return false
    }
    return {
      formatSize,
      toggleSelectAll,
      selectedImages,
      handleCheckRow,
      images,
      isShown,
      currentRole,
    }
  },
})
</script>

<style scoped>
.image-gallery {
  font-family:
    -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
  width: 100%;
  border-collapse: collapse;
  background-color: white;
  border-radius: 8px;
  max-height: 725px;
  overflow-y: scroll;
}

.gallery-header {
  display: flex;
  padding: 8px 0;
  border-bottom: 1px solid #e0e0e0;
  color: #666;
  font-weight: normal;
}

.gallery-row {
  display: flex;
  align-items: center;
  padding: 12px 0;
  border-bottom: 1px solid #f0f0f0;
}

.checkbox-cell {
  width: 80px;
  display: flex;
  align-items: center;
}

.image-name-cell {
  display: flex;
  align-items: center;
  flex: 1;
}

.image-preview {
  width: 200px;
  min-width: 200px;
  height: 100px;
  overflow: hidden;
  border-radius: 8px;
  margin-right: 16px;
}

.image-preview img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.name {
  font-weight: normal;
}

.header-cell {
  font-weight: 500;
  color: #666;
}

.name-cell {
  flex: 1;
  margin-left: 5rem;
}

.dimension-cell {
  width: 150px;
  text-align: left;
}

.size-cell {
  width: 120px;
  text-align: left;
}

.sort-icon {
  font-size: 12px;
  margin-left: 4px;
}

input[type='checkbox'] {
  width: 18px;
  height: 18px;
  margin-right: 6px;
  border-radius: 3px;
  border: 1px solid #ccc;
  accent-color: #1e90ff;
}

label {
  color: #666;
  font-size: 14px;
}
</style>
