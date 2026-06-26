

   <script setup>
   import { ref } from 'vue';
   import { useRoute, useRouter } from 'vue-router';
   import axios from 'axios';
  
   const route = useRoute();
   const router = useRouter();
  
   const form = ref({
     email: route.query.email || '',
     token: route.query.token || '',
     password: '',
     password_confirmation: ''
   });
  
   const error = ref('');
   const successMessage = ref('');
   const loading = ref(false);
  
   const handleReset = async () => {
     loading.value = true;
     error.value = '';
     successMessage.value = '';
     try {


       await axios.post('/api/reset-password', form.value);
       successMessage.value = 'Contraseña restablecida exitosamente. Redirigiendo al login...';
       setTimeout(() => {
         router.push('/');
       }, 3000);
     } catch (e) {
       if (e.response && e.response.status === 422) {
         error.value = e.response.data.message || 'El enlace ha expirado o es inválido. Por favor, solicita uno nuevo.';
       } else {
         error.value = 'Ocurrió un error inesperado al reestablecer la contraseña.';
       }
     } finally {
       loading.value = false;
     }
   };
   </script>
  
   <template>
     <div class="bg-gradient-clinical min-h-screen flex flex-col font-body-lg text-on-surface
 selection:bg-secondary-container overflow-hidden">
       <!-- Elementos decorativos -->
       <div class="absolute top-[-10%] right-[-5%] w-[40%] h-[40%] bg-secondary/5 rounded-full blur-[100px]
 pointer-events-none"></div>
       <div class="absolute bottom-[-10%] left-[-5%] w-[30%] h-[30%] bg-primary/5 rounded-full blur-[80px]
 pointer-events-none"></div>
  
       <div class="flex-grow flex flex-col items-center justify-center px-[20px] py-[32px] relative">
         <div class="glass-panel w-full max-w-[440px] p-[32px] rounded-lg animate-in fade-in zoom-in
 duration-700">
           
           <!-- Cabecera -->
           <div class="flex flex-col items-center text-center mb-[24px]">
             <span class="material-symbols-outlined text-[48px] text-primary mb-2">lock_open</span>
             <h1 class="font-headline-lg text-headline-lg text-primary tracking-tight">Nueva Contraseña</h1>
             <p class="font-body-sm text-body-sm text-on-surface-variant mt-[4px] max-w-[280px]">
               Configure una contraseña nueva y segura para acceder a su portal.
             </p>
           </div>
  
           <!-- Alertas de éxito y error -->
           <div v-if="successMessage" class="mb-4 p-4 bg-green-50 text-green-800 border border-green-200
 rounded-lg text-sm text-center font-medium animate-in fade-in duration-300">
             {{ successMessage }}
           </div>
           <div v-if="error" class="mb-4 p-4 bg-red-50 text-red-800 border border-red-200 rounded-lg text-sm
 text-center font-medium animate-in fade-in duration-300">
             {{ error }}
           </div>
  
           <!-- Formulario -->
           <form v-if="!successMessage" class="space-y-4" @submit.prevent="handleReset">
             
             <!-- Nueva Contraseña -->
             <div class="space-y-1">
               <label class="font-label-caps text-label-caps text-outline uppercase" for="password">
                 Nueva Contraseña
               </label>
               <div class="relative group">
                 <span class="material-symbols-outlined absolute left-[16px] top-1/2 -translate-y-1/2
 text-outline group-focus-within:text-primary transition-colors">lock</span>
                 <input
                   id="password"
                   v-model="form.password"
                   class="w-full pl-[44px] pr-[16px] py-[16px] bg-surface-container-lowest border
 border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none
 transition-all-custom text-body-lg"
                   placeholder="Mínimo 8 caracteres"
                   type="password"
                   required
                 />
               </div>
             </div>
  
             <!-- Confirmar Contraseña -->
             <div class="space-y-1">
               <label class="font-label-caps text-label-caps text-outline uppercase" for="password_confirmation">
                 Confirmar Contraseña
               </label>
               <div class="relative group">
                 <span class="material-symbols-outlined absolute left-[16px] top-1/2 -translate-y-1/2
 text-outline group-focus-within:text-primary transition-colors">lock</span>
                 <input
                   id="password_confirmation"
                   v-model="form.password_confirmation"
                   class="w-full pl-[44px] pr-[16px] py-[16px] bg-surface-container-lowest border
 border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none
 transition-all-custom text-body-lg"
                    placeholder="Repita la contraseña"
                    type="password"
                    required
                  />
                </div>
              </div>
   
              <!-- Botón de envío -->
              <button
                class="w-full py-[16px] bg-primary-container text-on-primary hover:bg-primary
 transition-all-custom font-button text-button rounded-lg shadow-sm active:scale-[0.98] mt-[24px]
 cursor-pointer"
                type="submit"
                :disabled="loading"
              >
                <span v-if="loading" class="flex items-center justify-center gap-2">
                  <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
 viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
 stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2
 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Guardando contraseña...
                </span>
                <span v-else class="flex items-center justify-center gap-1">
                  Establecer Contraseña
                  <span class="material-symbols-outlined text-[18px]">verified</span>
                </span>
              </button>
            </form>
   
            <!-- Retorno al Login -->
            <div class="mt-[24px] pt-[24px] border-t border-outline-variant/30 text-center">
              <router-link to="/" class="font-button text-secondary hover:underline transition-all">
                Cancelar y volver al login
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </template>
   
    <style scoped>
    .glass-panel {
      background: rgba(255, 255, 255, 0.7);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(0, 102, 136, 0.3);
      box-shadow: 0px 4px 20px rgba(0, 102, 136, 0.1);
    }
    .bg-gradient-clinical {
      background: linear-gradient(135deg, #f7f9fb 0%, #e3f2fd 50%, #f7f9fb 100%);
    }
    .transition-all-custom {
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    </style>
