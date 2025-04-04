<template>
  <v-card class="file-browser" max-width="300" flat>
    <v-card class="file-browser" max-width="300" flat>
      <div class="d-flex justify-space-between align-center">
        <p class="title text-subtitle-2 text-medium-emphasis">Folder</p>
        <span>
          <a class="text-decoration-underline change-plan text-subtitle-2 text-medium-emphasis">
            New folder</a
          >
        </span>
      </div>
      <v-list class="list-folder" nav density="compact">
        <div v-for="(folder, i) in folderData" :key="i">
          <!-- Main folder -->
          <v-list-item @click="toggleFolder(folder.id)" :active="selected === folder.id">
            <template v-slot:prepend>
              <v-icon :color="folder.expanded ? 'black' : 'grey-lighten-1'">
                {{ folder.expanded ? 'mdi-menu-down' : 'mdi-play' }}
              </v-icon>
              <v-icon
                :color="
                  selected === folder.id
                    ? 'blue'
                    : folder.expanded
                      ? 'blue-darken-1'
                      : 'grey-lighten-1'
                "
              >
                {{ folder.expanded ? 'mdi-folder-open' : 'mdi-folder' }}
              </v-icon>
            </template>
            <v-list-item-title
              :class="selected === folder.id ? 'text-blue' : ''"
              class="font-weight-medium"
              >{{ folder.name }}</v-list-item-title
            >
            <template v-slot:append>
              <v-chip
                size="x-small"
                :color="selected === folder.id ? 'blue' : 'grey-lighten-3'"
                class="text-white"
                :class="selected === folder.id ? 'selected-item' : 'text-caption'"
                >{{ folder.count }}</v-chip
              >
            </template>
          </v-list-item>
          <!-- Subfolders - shown only when parent is expanded -->
          <template v-if="folder.expanded">
            <v-list-item
              v-for="subfolder in folder.children"
              :key="subfolder.id"
              class="pl-6"
              @click="selectSubfolder(subfolder.id, subfolder.children)"
              :active="selected === subfolder.id"
            >
              <template v-slot:prepend>
                <v-icon :color="selected === subfolder.id ? 'blue' : 'grey-lighten-1'"
                  >mdi-play</v-icon
                >
                <v-icon :color="selected === subfolder.id ? 'blue' : 'grey-lighten-1'"
                  >mdi-folder</v-icon
                >
              </template>
              <v-list-item-title
                :class="selected === subfolder.id ? 'text-blue' : ''"
                class="font-weight-medium"
                >{{ subfolder.name }}</v-list-item-title
              >
              <template v-slot:append>
                <v-chip
                  size="x-small"
                  :color="selected === subfolder.id ? 'blue' : 'grey-lighten-3'"
                  class="text-white"
                  :class="selected === subfolder.id ? 'selected-item' : 'text-caption'"
                  >{{ subfolder.count }}</v-chip
                >
              </template>
            </v-list-item>
          </template>
        </div>
      </v-list>
    </v-card>
  </v-card>
</template>

<style lang="sass" scoped>
.file-browser
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif

.selected-item
  background-color: #1a73e8
  color:  white !important
  border-radius: 4px
.folder-count
    background-color: #c8d0de
    border-radius: 4px
    color: #5d6474
.text-caption
    border-radius: 4px
    background-color: #5d6474
    color : #151a3b
.list-folder
    ::v-deep .v-list-item__overlay
        background-color: #1a73e8
</style>

<script>
import { defineComponent, ref, computed, onMounted, watch } from 'vue'
import folderDataImport from '@/assets/data.json'
import { useGalleryList } from '@/store'

export default defineComponent({
  setup() {
    const folderData = ref()
    const selected = ref(1)
    const selectedFile = ref(null)
    const expandedSubfolders = ref([])
    const { currentImgList } = useGalleryList()
    // Helper function to count children
    const countChildren = (folder) => {
      if (!folder.children) return 0
      return folder.children.length
    }

    // Methods
    const toggleFolder = (folderId) => {
      // Find the folder in your data structure
      const folder = folderData.value.find((f) => f.id === folderId)
      // Toggle the expanded property
      if (folder) {
        currentImgList.value = []
        folder.children.forEach((item) => {
          //push all child in
          currentImgList.value = [...currentImgList.value, ...item.children]
        })
        folder.expanded = !folder.expanded
        selected.value = folderId // Optional: select the folder when toggled
      }
    }

    const selectSubfolder = (subfolderId, children) => {
      selected.value = subfolderId
      currentImgList.value = children
      // Toggle subfolder expansion
      if (expandedSubfolders.value.includes(subfolderId)) {
        expandedSubfolders.value = expandedSubfolders.value.filter((id) => id !== subfolderId)
      } else {
        expandedSubfolders.value.push(subfolderId)
      }
    }

    const selectFile = (fileId) => {
      selectedFile.value = fileId
    }

    onMounted(() => {
      // default pick upload folder
      folderData.value = folderDataImport.map((folder) => {
        // Calculate counts for main folders
        const count = countChildren(folder)

        // Process children to add counts for subfolders
        const processedChildren = folder.children
          ? folder.children.map((subfolder) => {
              return {
                ...subfolder,
                count: countChildren(subfolder),
              }
            })
          : []

        return {
          ...folder,
          count,
          children: processedChildren,
          expanded: folder.id === 1, // Default expand the Uploads folder
        }
      })

      toggleFolder(1)
    })
    return {
      selected,
      selectSubfolder,
      toggleFolder,
      selectFile,
      folderData,
    }
  },
})
</script>
