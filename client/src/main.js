import { createApp } from 'vue'
import { createPinia } from 'pinia'
import axios from 'axios'
import './style.css'
import App from './App.vue'
import router from './router'

// Configuración global de axios para Sanctum
axios.defaults.baseURL = 'http://localhost:8000';
axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.mount('#app')
