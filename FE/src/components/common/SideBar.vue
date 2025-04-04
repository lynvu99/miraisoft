<template>
  <div class="side-bar-wrapper element-padding">
    <div class="import-btn-wrapper">
      <UploadDocumentBtn />
      <Storage class="mt-5" />
      <v-divider class="mt-5"></v-divider>
      <SearchBar class="mt-5" />
      <v-divider class="mt-5"></v-divider>
      <FoldersStructure class="mt-5" />
      <v-divider class="mt-5"></v-divider>
      <Member class="mt-5" />
    </div>
  </div>
</template>
<script>
import { defineComponent } from 'vue'
import Storage from './Storage.vue'
import SearchBar from './SearchBar.vue'
import UploadDocumentBtn from './UploadDocumentBtn.vue'
import FoldersStructure from './FoldersStructure.vue'
import Member from './Member.vue'
export default defineComponent({
  components: {
    Storage, // Register the component
    SearchBar,
    UploadDocumentBtn,
    FoldersStructure,
    Member,
  },
  setup() {},
})
</script>

<style lang="sass" scoped>
.side-bar-wrapper
    background-color: white
    border-radius: 8px
    border: 1px solid #f3f4f6
    grid-column: 1
</style>
