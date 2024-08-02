require('./bootstrap')

import { createApp } from 'vue'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faShoppingCart } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import router from './router'

library.add(faShoppingCart)
const app = createApp({})

app.component('font-awesome-icon', FontAwesomeIcon)

app.use(router)

app.mount('#app')