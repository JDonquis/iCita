<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const personnel = ref([]);
const loading = ref(false);
const showModal = ref(false);
const editingPerson = ref(null);

const form = ref({
  full_name: '',
  ci: '',
  phone_number: '',
  email: '',
  role: 'doctor'
});

const fetchPersonnel = async () => {
  loading.value = true;
  const { data } = await axios.get('/api/personnel');
  personnel.value = data;
  loading.value = false;
};

const save = async () => {
  try {
    if (editingPerson.value) {
      await axios.put(`/api/personnel/${editingPerson.value.id}`, form.value);
    } else {
      await axios.post('/api/personnel', form.value);
    }
    showModal.value = false;
    fetchPersonnel();
  } catch (e) {
    alert('Error al guardar');
  }
};

const deletePerson = async (id) => {
  if (confirm('¿Eliminar?')) {
    await axios.delete(`/api/personnel/${id}`);
    fetchPersonnel();
  }
};

onMounted(fetchPersonnel);
</script>

<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-blue-900">Personal Médico</h1>
      <button @click="showModal = true; editingPerson = null" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
        Agregar Personal
      </button>
    </div>

    <!-- Modal Form -->
    <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4">
      <div class="bg-white p-6 rounded-lg w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">{{ editingPerson ? 'Editar' : 'Agregar' }} Personal</h2>
        <form @submit.prevent="save" class="space-y-4">
          <input v-model="form.full_name" placeholder="Nombre completo" class="w-full border p-2 rounded" required />
          <input v-model="form.ci" placeholder="Cédula" class="w-full border p-2 rounded" required />
          <input v-model="form.phone_number" placeholder="Teléfono" class="w-full border p-2 rounded" required />
          <input v-model="form.email" type="email" placeholder="Correo" class="w-full border p-2 rounded" required />
          <select v-model="form.role" class="w-full border p-2 rounded">
            <option value="doctor">Doctor</option>
            <option value="secretary">Secretaria</option>
          </select>
          <div class="flex justify-end space-x-2">
            <button type="button" @click="showModal = false" class="px-4 py-2 text-slate-600">Cancelar</button>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Guardar</button>
          </div>
        </form>
      </div>
    </div>

    <table class="w-full bg-white rounded-lg shadow border border-slate-200">
      <thead class="bg-slate-50 border-b">
        <tr>
          <th class="p-3 text-left">Nombre</th>
          <th class="p-3 text-left">Rol</th>
          <th class="p-3 text-left">Teléfono</th>
          <th class="p-3 text-left">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="person in personnel" :key="person.id" class="border-b">
          <td class="p-3">{{ person.full_name }}</td>
          <td class="p-3 capitalize">{{ person.users[0]?.role }}</td>
          <td class="p-3">{{ person.phone_number }}</td>
          <td class="p-3">
            <button @click="deletePerson(person.id)" class="text-red-500 hover:text-red-700">Eliminar</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
