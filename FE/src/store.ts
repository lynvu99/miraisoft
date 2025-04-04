import { createInjectionState } from '@vueuse/core'
import { computed, ref, watch, type Ref } from 'vue'
import folderDataImport from '@/assets/data.json'

interface Image {
  id: number
  name: string
  url: string
  type: string
  dimmension: string
  size: string
  photo_by: string
}
export const [useProvideGalleryList, useGalleryList] = createInjectionState(() => {
  const currentImgList = ref<Image[]>([])
  const currentUsage = computed(() => {
    return calculateImageSize(folderDataImport)
  })
  const currentRole = ref(['All'])
  const searchString = ref('')
  const calculateImageSize = (arr) => {
    let totalSize = 0

    function traverse(node) {
      if (node.type && node.type.startsWith('image/')) {
        totalSize += Number(node.size) // Convert string to number and add to total
      }
      if (node.children && node.children.length > 0) {
        node.children.forEach((child) => traverse(child))
      }
    }

    arr.forEach((item) => traverse(item))
    totalSize = (totalSize / 2147483648) * 100
    return totalSize.toFixed(2)
  }

  // Function to find images by name in the nested structure
  const findImagesByName = (arr, searchName) => {
    const results = []
    function traverse(node) {
      if (
        node.type &&
        node.type.startsWith('image/') &&
        node.name.toLowerCase() === searchName.toLowerCase()
      ) {
        results.push({ ...node }) // Add matching image to results
      }
      if (node.children && node.children.length > 0) {
        node.children.forEach((child) => traverse(child))
      }
    }
    arr.forEach((item) => traverse(item))
    return results
  }
  watch(
    () => searchString.value,
    (val) => {
      if (val.trim() === '') currentImgList.value = []
      else {
        const matchingImages: Image[] = findImagesByName(folderDataImport, searchString.value)
        currentImgList.value = matchingImages.length > 0 ? matchingImages : [] // Update with matches or empty array if none found
      }
    },
  )
  return { currentImgList, currentUsage, currentRole, searchString }
})
