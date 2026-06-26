<script setup>
import { ref, watch, onMounted } from 'vue';
import axios from 'axios';

const personnel = ref([]);
const loading = ref(false);
const showModal = ref(false);
const editingPerson = ref(null);
const deletingPerson = ref(null);
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

const fetchPersonnel = async () => {
  loading.value = true;
  const { data } = await axios.get('/api/personnel');
  personnel.value = data;
  loading.value = false;
};

const openCreateModal = () => {
  editingPerson.value = null;
  form.value = { full_name: '', ci: '', phone_number: '', email: '', role: 'doctor', specialty: '' };
  photoFile.value = null;
  photoPreview.value = null;
  errors.value = {};
  showModal.value = true;
};

const openEditModal = async (person) => {
  editingPerson.value = person;
  photoFile.value = null;
  photoPreview.value = null;
  errors.value = {};

  const user = person.users?.[0];
  form.value = {
    full_name: person.full_name,
    ci: person.ci || '',
    phone_number: person.phone_number || '',
    email: user?.email || '',
    role: user?.role || 'doctor',
    specialty: user?.doctor?.specialty || '',
  };

  if (person.photo && person.photo !== 'nophoto.webp') {
    photoPreview.value = photoUrl(person.photo);
  }

  showModal.value = true;
};

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

    if (editingPerson.value) {
      formData.append('_method', 'PUT');
      await axios.post(`/api/personnel/${editingPerson.value.id}`, formData);
    } else {
      await axios.post('/api/personnel', formData);
    }
    showModal.value = false;
    fetchPersonnel();
  } catch (e) {
    if (e.response?.status === 422) {
      errors.value = e.response.data.errors;
    } else {
      alert('Error al guardar');
    }
  }
};

const confirmDelete = (person) => {
  deletingPerson.value = person;
};

const cancelDelete = () => {
  deletingPerson.value = null;
};

const executeDelete = async () => {
  if (!deletingPerson.value) return;
  try {
    await axios.delete(`/api/personnel/${deletingPerson.value.id}`);
    deletingPerson.value = null;
    fetchPersonnel();
  } catch (e) {
    alert(e.response?.data?.message || 'Error al eliminar');
  }
};

const API_BASE = import.meta.env.VITE_API_URL || '';

const photoUrl = (path) => {
  if (!path || path === 'nophoto.webp') return `${API_BASE}/nophoto.webp`;
  return `${API_BASE}/storage/${path}`;
};

onMounted(fetchPersonnel);
</script>

<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-primary">Personal Médico</h1>
      <button @click="openCreateModal"
        class="bg-primary-container text-on-primary font-button text-button px-4 py-2 rounded-lg hover:bg-primary transition-all duration-300 shadow-sm active:scale-[0.98]">
        Agregar Personal
      </button>
    </div>

    <!-- Modal Formulario -->
    <div v-if="showModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center p-4 z-50">
      <div class="glass-panel w-full max-w-md p-8 rounded-xl animate-in fade-in zoom-in-95 duration-300">
        <h2 class="font-headline-md text-headline-md text-primary mb-6">{{ editingPerson ? 'Editar' : 'Agregar' }} Personal</h2>
        <form @submit.prevent="save" class="space-y-5">

          <!-- Foto -->
          <div class="space-y-2">
            <label class="font-label-caps text-label-caps text-outline uppercase">Foto de perfil</label>
            <div class="flex items-center gap-4">
              <div class="w-16 h-16 rounded-full overflow-hidden bg-surface-container border border-outline-variant shrink-0">
                <img v-if="photoPreview" :src="photoPreview" class="w-full h-full object-cover" />
                <div v-else class="w-full h-full flex items-center justify-center text-outline">
                  <span class="material-symbols-outlined" style="font-size:32px">person</span>
                </div>
              </div>
              <label class="cursor-pointer bg-surface-container-lowest border border-outline-variant rounded-lg px-4 py-2 font-body-sm text-body-sm text-on-surface hover:border-primary transition-all-custom">
                <span>Seleccionar archivo</span>
                <input type="file" accept="image/*" @change="handlePhotoChange" class="hidden" />
              </label>
            </div>
            <p v-if="errors.photo" class="text-error text-body-sm font-body-sm">{{ errors.photo[0] }}</p>
          </div>

          <!-- Nombre completo -->
          <div class="space-y-1">
            <label class="font-label-caps text-label-caps text-outline uppercase">Nombre completo</label>
            <input v-model="form.full_name" placeholder="Nombre completo"
              class="w-full px-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all-custom text-body-lg" />
            <p v-if="errors.full_name" class="text-error text-body-sm font-body-sm">{{ errors.full_name[0] }}</p>
          </div>

          <!-- Cédula -->
          <div class="space-y-1">
            <label class="font-label-caps text-label-caps text-outline uppercase">Cédula</label>
            <input v-model="form.ci" placeholder="Cédula"
              class="w-full px-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all-custom text-body-lg" />
            <p v-if="errors.ci" class="text-error text-body-sm font-body-sm">{{ errors.ci[0] }}</p>
          </div>

          <!-- Teléfono -->
          <div class="space-y-1">
            <label class="font-label-caps text-label-caps text-outline uppercase">Teléfono</label>
            <input v-model="form.phone_number" placeholder="Teléfono"
              class="w-full px-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all-custom text-body-lg" />
            <p v-if="errors.phone_number" class="text-error text-body-sm font-body-sm">{{ errors.phone_number[0] }}</p>
          </div>

          <!-- Correo -->
          <div class="space-y-1">
            <label class="font-label-caps text-label-caps text-outline uppercase">Correo electrónico</label>
            <input v-model="form.email" type="email" placeholder="Correo"
              class="w-full px-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all-custom text-body-lg" />
            <p v-if="errors.email" class="text-error text-body-sm font-body-sm">{{ errors.email[0] }}</p>
          </div>

          <!-- Rol -->
          <div class="space-y-1">
            <label class="font-label-caps text-label-caps text-outline uppercase">Rol</label>
            <select v-model="form.role"
              class="w-full px-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all-custom text-body-lg">
              <option value="doctor">Doctor</option>
              <option value="secretary">Secretaria</option>
            </select>
            <p v-if="errors.role" class="text-error text-body-sm font-body-sm">{{ errors.role[0] }}</p>
          </div>

          <!-- Especialidad (solo para doctor) -->
          <div v-if="form.role === 'doctor'" class="space-y-1">
            <label class="font-label-caps text-label-caps text-outline uppercase">Especialidad</label>
            <input v-model="form.specialty" placeholder="Ej: Cardiología, Pediatría..."
              class="w-full px-4 py-3 bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all-custom text-body-lg" />
            <p v-if="errors.specialty" class="text-error text-body-sm font-body-sm">{{ errors.specialty[0] }}</p>
          </div>

          <!-- Botones -->
          <div class="flex justify-end gap-3 pt-2">
            <button type="button" @click="showModal = false"
              class="px-5 py-2.5 bg-transparent text-secondary font-button text-button rounded-lg border border-secondary/30 hover:bg-secondary/5 transition-all-custom active:scale-[0.98]">
              Cancelar
            </button>
            <button type="submit"
              class="px-5 py-2.5 bg-primary-container text-on-primary font-button text-button rounded-lg hover:bg-primary transition-all-custom shadow-sm active:scale-[0.98]">
              {{ editingPerson ? 'Actualizar' : 'Guardar' }}
            </button>
          </div>

        </form>
      </div>
    </div>

    <!-- Modal Confirmación Eliminar -->
    <div v-if="deletingPerson" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center p-4 z-50">
      <div class="glass-panel w-full max-w-sm p-6 rounded-xl animate-in fade-in zoom-in-95 duration-200 text-center">
        <span class="material-symbols-outlined text-tertiary mb-3" style="font-size:48px">warning</span>
        <h3 class="font-headline-md text-headline-md text-on-surface mb-2">¿Eliminar personal?</h3>
        <p class="font-body-sm text-body-sm text-on-surface-variant mb-6">
          Se eliminará permanentemente a <strong>{{ deletingPerson.full_name }}</strong> y todos sus datos asociados.
        </p>
        <div class="flex justify-center gap-3">
          <button @click="cancelDelete"
            class="px-5 py-2.5 bg-transparent text-secondary font-button text-button rounded-lg border border-secondary/30 hover:bg-secondary/5 transition-all-custom active:scale-[0.98]">
            Cancelar
          </button>
          <button @click="executeDelete"
            class="px-5 py-2.5 bg-tertiary text-on-tertiary font-button text-button rounded-lg hover:bg-tertiary-container hover:text-on-tertiary-container transition-all-custom shadow-sm active:scale-[0.98]">
            Eliminar
          </button>
        </div>
      </div>
    </div>

    <!-- Tabla -->
    <div class="glass-panel rounded-xl overflow-hidden">
      <table class="w-full">
        <thead class="bg-surface-container-low border-b border-outline-variant/30">
          <tr>
            <th class="p-4 text-left font-label-caps text-label-caps text-outline uppercase">Foto</th>
            <th class="p-4 text-left font-label-caps text-label-caps text-outline uppercase">Nombre</th>
            <th class="p-4 text-left font-label-caps text-label-caps text-outline uppercase">Correo</th>
            <th class="p-4 text-left font-label-caps text-label-caps text-outline uppercase">Rol</th>
            <th class="p-4 text-left font-label-caps text-label-caps text-outline uppercase">Especialidad</th>
            <th class="p-4 text-left font-label-caps text-label-caps text-outline uppercase">Teléfono</th>
            <th class="p-4 text-left font-label-caps text-label-caps text-outline uppercase">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="person in personnel" :key="person.id"
            class="border-b border-outline-variant/20 hover:bg-surface-container-lowest/50 transition-colors">
            <td class="p-4">
              <img :src="photoUrl(person.photo)" alt="Foto"
                class="w-10 h-10 rounded-full object-cover border border-outline-variant/30" />
            </td>
            <td class="p-4 text-body-lg text-on-surface">{{ person.full_name }}</td>
            <td class="p-4 text-body-sm text-on-surface-variant">{{ person.users[0]?.email }}</td>
            <td class="p-4 capitalize text-body-sm">
              <span class="inline-block px-3 py-1 rounded-full text-label-caps font-label-caps"
                :class="person.users[0]?.role === 'doctor' ? 'bg-primary/10 text-primary' : 'bg-secondary/10 text-secondary'">
                {{ person.users[0]?.role }}
              </span>
            </td>
            <td class="p-4 text-body-sm text-on-surface-variant">
              {{ person.users[0]?.doctor?.specialty || '—' }}
            </td>
            <td class="p-4 text-body-sm text-on-surface-variant">{{ person.phone_number }}</td>
            <td class="p-4">
              <div class="flex gap-2">
                <button @click="openEditModal(person)"
                  class="text-primary hover:text-primary-container font-button text-button transition-colors active:scale-[0.98]">
                  Editar
                </button>
                <button @click="confirmDelete(person)"
                  class="text-tertiary hover:text-tertiary-container font-button text-button transition-colors active:scale-[0.98]">
                  Eliminar
                </button>
              </div>
            </td>
          </tr>
          <tr v-if="!loading && personnel.length === 0">
            <td colspan="7" class="p-8 text-center text-outline font-body-sm">
              No hay personal registrado
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped>
.glass-panel {
  background: rgba(255, 255, 255, 0.75);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  border: 1px solid rgba(0, 102, 136, 0.2);
  box-shadow: 0px 4px 24px rgba(0, 102, 136, 0.08);
}

.transition-all-custom {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
