import './assets/main.css'
import 'vuetify/styles'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import { createVuetify } from 'vuetify'

import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import { aliases, mdi } from 'vuetify/iconsets/mdi'
import { mdiMagnify } from '@mdi/js' // Import the specific icon

const app = createApp(App)
const vuetify = createVuetify({
  components,
  directives,
  icons: {
    defaultSet: 'mdi',
    aliases: {
      ...aliases,
      magnify: mdiMagnify, // Map the alias to the SVG path
    },
    sets: {
      mdi,
    },
  },
})

app.use(vuetify)
app.use(router)
app.mount('#app')
