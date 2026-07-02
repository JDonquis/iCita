<script>
export default { name: 'personnelCreate' }
</script>

<script setup>
import { ref, watch } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import AppLayout from '../../Layouts/AppLayout.vue';

const router = useRouter();
const loading = ref(false);
const errors = ref({});
const photoFile = ref(null);
const photoPreview = ref(null);

const form = ref({
  full_name: '',
  ci: '',
  phone_number: '',
  email: '',
  role: 'doctor',
  specialty: '',
});

watch(() => form.value.role, (newRole) => {
  if (newRole !== 'doctor') {
    form.value.specialty = '';
  }
});

const handlePhotoChange = (e) => {
  const file = e.target.files[0];
  if (file) {
    photoFile.value = file;
    photoPreview.value = URL.createObjectURL(file);
  } else {
    photoFile.value = null;
    photoPreview.value = null;
  }
};

const save = async () => {
  errors.value = {};
  loading.value = true;
  try {
    const formData = new FormData();
    formData.append('full_name', form.value.full_name);
    formData.append('ci', form.value.ci);
    formData.append('phone_number', form.value.phone_number);
    formData.append('email', form.value.email);
    formData.append('role', form.value.role);
    if (photoFile.value) {
      formData.append('photo', photoFile.value);
    }
    if (form.value.role === 'doctor') {
      formData.append('specialty', form.value.specialty || '');
    }
    
    await axios.post('/api/personnel', formData);
    router.push('/personnel');
  } catch (e) {
    if (e.response?.status === 422) {
      errors.value = e.response.data.errors;
    } else {
      alert('Error al crear el registro');
    }
  } finally {
    loading.value = false;
  }
};
</script>

<template>
<AppLayout titulo="Personal Médico" subtitulo="Crear nuevo registro de personal">
  <div class="p-4 md:p-8 flex-1 overflow-y-auto">
    <!-- Header (Mobile / PC) -->
    <header class="flex justify-between items-center mb-6 md:mb-8">
      <div class="flex items-center gap-2 md:gap-4">
        <div class="hidden md:block w-1.5 h-8 bg-primary rounded-full"></div>
        <h2 class="text-headline-lg-mobile md:text-headline-lg font-headline-lg-mobile md:font-headline-lg text-primary tracking-tight">Agregar Personal</h2>
      </div>
      <button @click="router.push('/personnel')" aria-label="Cerrar"
        class="w-10 h-10 rounded-full flex items-center justify-center bg-surface-container-highest/50 hover:bg-surface-container-highest transition-colors active:scale-95 text-on-surface-variant">
        <span class="material-symbols-outlined">close</span>
      </button>
    </header>

    <div class="glass-panel rounded-2xl md:rounded-[32px] overflow-hidden flex flex-col p-6 md:p-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
      <form @submit.prevent="save">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-12">
          
          <!-- Profile Section (Left on PC, Top on Mobile) -->
          <div class="col-span-1 md:col-span-12 lg:col-span-4 flex flex-col items-center justify-center md:border-r md:border-secondary/10 md:pr-8">
            <div class="relative group">
              <div class="w-32 h-32 md:w-48 md:h-48 rounded-full border-4 border-white shadow-md md:shadow-xl overflow-hidden bg-surface-container-high flex items-center justify-center">
                <img v-if="photoPreview" :src="photoPreview" class="w-full h-full object-cover" />
                <span v-else class="material-symbols-outlined text-outline-variant" style="font-size: 80px; font-variation-settings: 'FILL' 1;">person</span>
                
                <label class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer">
                  <span class="material-symbols-outlined text-white text-[48px]">photo_camera</span>
                  <input type="file" accept="image/*" @change="handlePhotoChange" class="hidden" />
                </label>
              </div>
              <label class="hidden md:flex absolute bottom-2 right-2 w-12 h-12 bg-primary text-white rounded-full items-center justify-center shadow-lg border-2 border-white cursor-pointer hover:bg-primary-container transition-colors">
                <span class="material-symbols-outlined">edit</span>
                <input type="file" accept="image/*" @change="handlePhotoChange" class="hidden" />
              </label>
            </div>
            <div class="mt-4 md:mt-6 text-center">
              <label class="px-4 py-2 rounded-xl border border-primary text-primary font-button text-button hover:bg-primary/5 transition-colors cursor-pointer inline-block">
                Subir Foto
                <input type="file" accept="image/*" @change="handlePhotoChange" class="hidden" />
              </label>
              <p class="mt-2 text-body-sm text-outline font-body-sm">Formatos: JPG, PNG. Máx 5MB.</p>
              <p v-if="errors.photo" class="text-error text-body-sm font-body-sm mt-1">{{ errors.photo[0] }}</p>
            </div>
          </div>

          <!-- Form Fields -->
          <div class="col-span-1 md:col-span-12 lg:col-span-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              
              <!-- Full Name -->
              <div class="flex flex-col gap-1">
                <label class="text-label-caps font-label-caps text-on-surface-variant ml-2">Nombre Completo</label>
                <div class="relative group">
                  <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">person</span>
                  <input v-model="form.full_name" class="w-full pl-12 pr-4 py-3 bg-white/50 border border-secondary/20 rounded-2xl text-body-lg font-body-lg placeholder:text-outline-variant focus:ring-2 focus:ring-secondary-container focus:border-transparent outline-none transition-all" placeholder="Ej. Dr. Julian Smith" type="text" />
                </div>
                <p v-if="errors.full_name" class="text-error text-body-sm font-body-sm ml-2">{{ errors.full_name[0] }}</p>
              </div>

              <!-- Role Selector -->
              <div class="flex flex-col gap-1">
                <label class="text-label-caps font-label-caps text-on-surface-variant ml-2">Rol / Cargo</label>
                <div class="relative">
                  <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">clinical_notes</span>
                  <select v-model="form.role" class="w-full pl-12 pr-4 py-3 bg-white/50 border border-secondary/20 rounded-2xl text-body-lg font-body-lg appearance-none cursor-pointer focus:ring-2 focus:ring-secondary-container focus:border-transparent outline-none transition-all">
                    <option value="doctor">Doctor</option>
                    <option value="secretary">Secretaria</option>
                  </select>
                  <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-outline pointer-events-none">expand_more</span>
                </div>
                <p v-if="errors.role" class="text-error text-body-sm font-body-sm ml-2">{{ errors.role[0] }}</p>
              </div>

              <!-- ID / CI -->
              <div class="flex flex-col gap-1">
                <label class="text-label-caps font-label-caps text-on-surface-variant ml-2">Documento de Identidad (CI)</label>
                <div class="relative group">
                  <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">badge</span>
                  <input v-model="form.ci" class="w-full pl-12 pr-4 py-3 bg-white/50 border border-secondary/20 rounded-2xl text-body-lg font-body-lg placeholder:text-outline-variant focus:ring-2 focus:ring-secondary-container focus:border-transparent outline-none transition-all" placeholder="12.345.678-9" type="text" />
                </div>
                <p v-if="errors.ci" class="text-error text-body-sm font-body-sm ml-2">{{ errors.ci[0] }}</p>
              </div>

              <!-- Phone -->
              <div class="flex flex-col gap-1">
                <label class="text-label-caps font-label-caps text-on-surface-variant ml-2">Teléfono de Contacto</label>
                <div class="relative group">
                  <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">call</span>
                  <input v-model="form.phone_number" class="w-full pl-12 pr-4 py-3 bg-white/50 border border-secondary/20 rounded-2xl text-body-lg font-body-lg placeholder:text-outline-variant focus:ring-2 focus:ring-secondary-container focus:border-transparent outline-none transition-all" placeholder="+598 099 000 000" type="tel" />
                </div>
                <p v-if="errors.phone_number" class="text-error text-body-sm font-body-sm ml-2">{{ errors.phone_number[0] }}</p>
              </div>

              <!-- Email -->
              <div class="col-span-1 md:col-span-2 flex flex-col gap-1">
                <label class="text-label-caps font-label-caps text-on-surface-variant ml-2">Correo Electrónico</label>
                <div class="relative group">
                  <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">mail</span>
                  <input v-model="form.email" class="w-full pl-12 pr-4 py-3 bg-white/50 border border-secondary/20 rounded-2xl text-body-lg font-body-lg placeholder:text-outline-variant focus:ring-2 focus:ring-secondary-container focus:border-transparent outline-none transition-all" placeholder="usuario@icita.com" type="email" />
                </div>
                <p v-if="errors.email" class="text-error text-body-sm font-body-sm ml-2">{{ errors.email[0] }}</p>
              </div>

              <!-- Specialty (only if doctor) -->
              <div v-if="form.role === 'doctor'" class="col-span-1 md:col-span-2 flex flex-col gap-1 animate-in fade-in">
                <label class="text-label-caps font-label-caps text-on-surface-variant ml-2">Especialidad</label>
                <div class="relative group">
                  <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">medical_information</span>
                  <input v-model="form.specialty" class="w-full pl-12 pr-4 py-3 bg-white/50 border border-secondary/20 rounded-2xl text-body-lg font-body-lg placeholder:text-outline-variant focus:ring-2 focus:ring-secondary-container focus:border-transparent outline-none transition-all" placeholder="Ej. Cardiología" type="text" />
                </div>
                <p v-if="errors.specialty" class="text-error text-body-sm font-body-sm ml-2">{{ errors.specialty[0] }}</p>
              </div>
            </div>

            <!-- Additional Information Note (PC only) -->
            <div class="hidden md:block bg-primary/5 p-4 rounded-2xl border border-primary/10 mt-6">
              <div class="flex items-center gap-2 mb-2 text-primary">
                <span class="material-symbols-outlined text-md">info</span>
                <span class="text-label-caps font-label-caps">Nota Administrativa</span>
              </div>
              <p class="text-body-sm font-body-sm text-on-surface-variant">El personal registrado tendrá acceso a sus funciones específicas según el rol asignado. Puede recibir un correo con sus credenciales de acceso.</p>
            </div>
          </div>
        </div>

        <!-- Footer Actions -->
        <footer class="mt-6 md:mt-8 pt-6 border-t border-secondary/10 flex flex-col-reverse md:flex-row justify-end gap-3 md:gap-4">
          <button type="button" @click="router.push('/personnel')"
            class="w-full md:w-auto px-8 py-3 rounded-xl md:rounded-2xl border border-outline text-outline font-button text-button hover:bg-surface-container active:scale-95 transition-all text-center">
            Cancelar
          </button>
          <button type="submit" :disabled="loading"
            class="w-full md:w-auto px-8 py-3 rounded-xl md:rounded-2xl bg-primary-container md:bg-primary text-on-primary-container md:text-white font-button text-button shadow-lg shadow-primary/20 hover:bg-primary hover:text-white md:hover:bg-primary-container active:scale-95 transition-all flex items-center justify-center gap-2">
            <span class="material-symbols-outlined" :class="{ 'animate-spin': loading }">{{ loading ? 'sync' : 'save' }}</span>
            Guardar Personal
          </button>
        </footer>
      </form>
    </div>
    
    <!-- Informative Note (Mobile only) -->
    <p class="md:hidden mt-6 text-center font-body-sm text-body-sm text-outline-variant max-w-[80%] mx-auto">
      El personal registrado tendrá acceso a sus funciones específicas según el rol asignado.
    </p>
  </div>
</AppLayout>
</template>

<style scoped>
.glass-panel {
  background: rgba(255, 255, 255, 0.75);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  border: 1px solid rgba(0, 94, 150, 0.15);
  box-shadow: 0px 4px 20px rgba(0, 94, 150, 0.05);
}
</style>