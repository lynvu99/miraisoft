<template>
  <div class="side-bar-wrapper">
    <div class="import-btn-wrapper">
      <v-btn block variant="outlined" @click="triggerFileUpload">Import Documents</v-btn>
      <input
        type="file"
        ref="fileInput"
        @change="handleFileUpload"
        style="display: none"
        multiple
      />
    </div>
  </div>
</template>

<script>
import { defineComponent, ref } from 'vue'

export default defineComponent({
  setup() {
    const fileInput = ref(null)

    const triggerFileUpload = () => {
      fileInput.value.click()
    }

    const handleFileUpload = (event) => {
      const files = event.target.files
      if (files.length > 0) {
        // Handle file upload logic here
        uploadFiles(files)
      }
    }

    const uploadFiles = (files) => {
      for (let i = 0; i < files.length; i++) {
        const file = files[i]
        // Here you would typically handle the file (e.g., sending it to a server)
        // For example, use FormData to send to a server
        // const formData = new FormData();
        // formData.append('file', file);
        // Use fetch or axios to upload
      }
      // Clear the file input after uploading
      fileInput.value.value = null
    }

    return {
      fileInput,
      triggerFileUpload,
      handleFileUpload,
    }
  },
})
</script>

<style lang="sass" scoped>
.side-bar-wrapper
    background-color: white
    border-radius: 8px
    border: 1px solid #f3f4f6
    grid-column: 1

    .import-button-wrapper
    ::v-deep .v-btn
      height: 45px
      background-color: #1a73e8
      color: white
      padding: 10px 20px
      border: none
      border-radius: 4px
      text-transform: none
      .import-button-wrapper
      ::v-deep .v-btn:hover
        background-color: #174ea6


      .import-button-wrapper
      ::v-deep .v-btn:active
        background-color: #154398
</style>
