<script>
  export default { name: 'personnelIndex' }
</script>

<script setup>
  import { useAuthStore } from '../../stores/auth';
  import { useRouter } from 'vue-router';
  import AppLayout from '../../Layouts/AppLayout.vue';

  import { ref, watch, onMounted } from 'vue';
  import axios from 'axios';

  const authStore = useAuthStore();
  const router = useRouter();

  const handleLogout = async () => {
    await authStore.logout();
    router.push('/');
  };

  const personnel = ref([]);
  const loading = ref(false);
  const deletingUser = ref(null); // Ahora rastreamos el usuario

  const fetchPersonnel = async () => {
    loading.value = true;
    const { data } = await axios.get('/api/personnel');
    personnel.value = data;
    loading.value = false;
  };

  const openCreateModal = () => {
    router.push('/personnel/create');
  };

  const openEditModal = (user) => {
    router.push(`/personnel/edit/${user.person.id}`);
  };

  const confirmDelete = (user) => {
    deletingUser.value = user;
  };

  const cancelDelete = () => {
    deletingUser.value = null;
  };

  const executeDelete = async () => {
    if (!deletingUser.value) return;
    try {
      // Se sigue enviando el ID de la persona
      await axios.delete(`/api/personnel/${deletingUser.value.person.id}`);
      deletingUser.value = null;
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
<AppLayout titulo="Personal Medico" subtitulo="Bienvenido al personal medico">
  
  <div class="p-4 md:p-8 flex-1 overflow-y-auto">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 md:gap-0 mb-6">
      <div class="hidden md:block">
        <h2 class="font-headline-lg text-headline-lg text-primary">Gestión de Personal</h2>
        <p class="text-on-surface-variant font-body-lg">Administra el personal médico, horarios y permisos de acceso.</p>
      </div>
      <div class="md:hidden flex flex-col gap-4 w-full">
        <h2 class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">Gestión de Personal</h2>
      </div>
      <button @click="openCreateModal"
        class="w-full md:w-auto bg-primary-container text-on-primary font-button text-button py-3 px-6 rounded-xl flex items-center justify-center gap-2 shadow-lg shadow-primary/20 hover:brightness-110 active:scale-[0.98] transition-all">
        <span class="material-symbols-outlined">person_add</span>
        Agregar Personal
      </button>
    </div>

    <!-- Search Bar -->
    <div class="relative mb-6 md:mb-8">
      <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">search</span>
      <input type="text"
        class="w-full pl-12 pr-4 py-3 rounded-xl border border-outline-variant bg-surface-container-lowest focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-outline/60 text-body-lg"
        placeholder="Buscar por nombre o cargo..." />
    </div>


    <!-- Modal Confirmación Eliminar -->
    <div v-if="deletingUser" class="fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center p-4 z-50">
      <div class="glass-panel w-full max-w-sm p-6 rounded-xl animate-in fade-in zoom-in-95 duration-200 text-center">
        <span class="material-symbols-outlined text-tertiary mb-3" style="font-size:48px">warning</span>
        <h3 class="font-headline-md text-headline-md text-on-surface mb-2">¿Eliminar personal?</h3>
        <p class="font-body-sm text-body-sm text-on-surface-variant mb-6">
          Se eliminará permanentemente a <strong>{{ deletingUser.person.full_name }}</strong> y todos sus datos asociados.
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

    <!-- Responsive List/Table -->
    <!-- Mobile View (Cards) -->
    <section class="flex flex-col gap-4 md:hidden">
      <div v-for="user in personnel" :key="user.id" class="glass-card p-4 rounded-2xl flex items-center gap-4 shadow-sm">
         <div class="w-14 h-14 rounded-full bg-surface-container border border-primary/10 flex items-center justify-center overflow-hidden shrink-0">
           <img v-if="user.person.photo && user.person.photo !== 'nophoto.webp'" :src="photoUrl(user.person.photo)" class="w-full h-full object-cover" />
           <span v-else class="material-symbols-outlined text-outline text-3xl">person</span>
         </div>
         <div class="flex-1 min-w-0">
           <h3 class="font-headline-md text-on-surface truncate">{{ user.person.full_name }}</h3>
           <div class="flex items-center gap-1 text-on-surface-variant">
             <span class="material-symbols-outlined text-[16px]">call</span>
             <p class="text-body-sm font-body-sm">{{ user.person.phone_number }}</p>
           </div>
         </div>
         <div class="flex flex-col items-end gap-2">
           <span :class="user.role === 'doctor' ? 'bg-primary/10 text-primary' : 'bg-secondary-container/20 text-secondary'"
             class="px-2 py-1 rounded-full font-label-caps text-[10px] uppercase">
             {{ user.role }}
           </span>
           <div class="flex gap-1 mt-1">
             <button @click="openEditModal(user)" class="text-outline hover:text-primary p-1 transition-colors">
               <span class="material-symbols-outlined text-[20px]">edit</span>
             </button>
             <button @click="confirmDelete(user)" class="text-outline hover:text-error p-1 transition-colors">
               <span class="material-symbols-outlined text-[20px]">delete</span>
             </button>
           </div>
         </div>
      </div>
      <div v-if="!loading && personnel.length === 0" class="text-center p-8 text-on-surface-variant">
        No hay personal registrado
      </div>
    </section>

    <!-- PC View (Table) -->
    <div class="hidden md:block glass-panel rounded-2xl overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full border-collapse text-left">
          <thead>
            <tr class="border-b border-secondary-container/20">
              <th class="px-6 py-4 font-label-caps text-label-caps text-on-surface-variant opacity-60">PERSONAL</th>
              <th class="px-6 py-4 font-label-caps text-label-caps text-on-surface-variant opacity-60">ROL</th>
              <th class="px-6 py-4 font-label-caps text-label-caps text-on-surface-variant opacity-60">TELÉFONO</th>
              <th class="px-6 py-4 font-label-caps text-label-caps text-on-surface-variant opacity-60">CORREO</th>
              <th class="px-6 py-4 font-label-caps text-label-caps text-on-surface-variant opacity-60 text-right">ACCIONES</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-secondary-container/10">
            <tr v-for="user in personnel" :key="user.id" class="hover:bg-primary/5 transition-colors group">
              <td class="px-6 py-4">
                <div class="flex items-center gap-4">
                  <div class="w-12 h-12 rounded-full overflow-hidden bg-surface-container border border-primary/10 shrink-0 flex items-center justify-center">
                    <img v-if="user.person.photo && user.person.photo !== 'nophoto.webp'" :src="photoUrl(user.person.photo)" class="w-full h-full object-cover" />
                    <span v-else class="material-symbols-outlined text-outline text-3xl">person</span>
                  </div>
                  <div>
                    <p class="font-button text-on-surface">{{ user.person.full_name }}</p>
                    <p class="font-body-sm text-on-surface-variant">{{ user.role === 'doctor' ? (user.doctor?.specialty || 'Especialista') : 'Administrativo' }}</p>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4">
                <span :class="user.role === 'doctor' ? 'bg-primary-container text-on-primary' : 'bg-secondary-fixed text-on-secondary-fixed'"
                  class="px-2 py-1 rounded-lg font-label-caps text-[10px] uppercase">
                  {{ user.role }}
                </span>
              </td>
              <td class="px-6 py-4 font-body-sm text-on-surface-variant">{{ user.person.phone_number }}</td>
              <td class="px-6 py-4 font-body-sm text-on-surface-variant">{{ user.email }}</td>
              <td class="px-6 py-4 text-right">
                <div class="flex justify-end gap-2">
                  <button @click="openEditModal(user)" class="p-2 hover:bg-primary/10 text-primary rounded-lg transition-colors">
                    <span class="material-symbols-outlined">edit</span>
                  </button>
                  <button @click="confirmDelete(user)" class="p-2 hover:bg-error/10 text-error rounded-lg transition-colors">
                    <span class="material-symbols-outlined">delete</span>
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="!loading && personnel.length === 0">
              <td colspan="5" class="px-6 py-8 text-center text-on-surface-variant font-body-sm">
                No hay personal registrado
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- Pagination / Footer can go here -->
    </div>
    
    <!-- Role Summary Widgets (Only PC for now as per design) -->
    <div class="hidden md:grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
      <div class="glass-panel p-6 rounded-2xl flex items-center gap-6">
          <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
              <span class="material-symbols-outlined text-4xl" style="font-variation-settings: 'FILL' 1;">medical_services</span>
          </div>
          <div>
              <p class="font-label-caps text-label-caps text-on-surface-variant opacity-60">TOTAL DOCTORES</p>
              <h3 class="font-headline-md text-headline-md">{{ personnel.filter(p => p.role === 'doctor').length }}</h3>
          </div>
      </div>
      <div class="glass-panel p-6 rounded-2xl flex items-center gap-6">
          <div class="w-14 h-14 rounded-xl bg-secondary-container/20 flex items-center justify-center text-secondary">
              <span class="material-symbols-outlined text-4xl" style="font-variation-settings: 'FILL' 1;">support_agent</span>
          </div>
          <div>
              <p class="font-label-caps text-label-caps text-on-surface-variant opacity-60">TOTAL STAFF</p>
              <h3 class="font-headline-md text-headline-md">{{ personnel.filter(p => p.role !== 'doctor').length }}</h3>
          </div>
      </div>
      <div class="glass-panel p-6 rounded-2xl flex items-center gap-6">
          <div class="w-14 h-14 rounded-xl bg-green-100 flex items-center justify-center text-green-700">
              <span class="material-symbols-outlined text-4xl" style="font-variation-settings: 'FILL' 1;">how_to_reg</span>
          </div>
          <div>
              <p class="font-label-caps text-label-caps text-on-surface-variant opacity-60">TOTAL PERSONAL</p>
              <h3 class="font-headline-md text-headline-md">{{ personnel.length }}</h3>
          </div>
      </div>
    </div>
  </div>

</AppLayout>
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
