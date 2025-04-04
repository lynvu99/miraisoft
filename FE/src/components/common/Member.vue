<template>
  <div class="member-wrapper">
    <div class="d-flex justify-space-between storage-header mb-2">
      <p class="title text-subtitle-2 text-medium-emphasis">Members</p>
      <span>
        <a
          class="text-decoration-underline change-plan text-subtitle-2 text-medium-emphasis cursor-pointer"
          @click="handleSelectAll"
        >
          Select All</a
        >
      </span>
    </div>
    <div class="checkbox-container" v-for="(item, index) in listSelect" :key="index">
      <div class="checkbox-item">
        <input type="checkbox" :id="item.id" v-model="currentRole" :value="item.value" />
        <label class="ml-2" :for="item.id">{{ item.label }}</label>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref } from 'vue'
import { useGalleryList } from '@/store'

export default defineComponent({
  setup() {
    const { currentRole } = useGalleryList()

    const listSelect = ref([
      {
        label: 'All',
        value: 'All',
        id: 'all-checkbox',
      },
      { label: 'Admin', value: 'Admin', id: 'admin-checkbox' },
    ])
    const handleSelectAll = () => {
      listSelect.value.forEach((item) => {
        currentRole.value.push(item.value)
      })
    }
    return {
      listSelect,
      handleSelectAll,
      currentRole,
    }
  },
})
</script>

<style lang="sass" scoped></style>
