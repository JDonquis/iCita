<script setup>
     import { ref } from 'vue';
     import axios from 'axios';
    
     const email = ref('');
     const message = ref('');
     const error = ref('');
     const loading = ref(false);
    
     const handleForgotPassword = async () => {
       loading.value = true;
       message.value = '';
       error.value = '';
       try {
        await axios.get('/sanctum/csrf-cookie');

         const response = await axios.post('/api/forgot-password', { email: email.value });
         message.value = response.data.message || 'Se ha enviado un enlace a su bandeja de entrada.';
         email.value = '';
       } catch (e) {
         if (e.response && e.response.status === 422) {
           error.value = e.response.data.errors?.email?.[0] || e.response.data.message || 'El correo electrónico no está registrado.';
         } else {
           error.value = 'Ocurrió un error inesperado. Por favor, inténtelo de nuevo.';
         }
       } finally {
         loading.value = false;
       }
     };
     </script>
    
     <template>
       <div class="bg-gradient-clinical min-h-screen flex flex-col font-body-lg text-on-surface
 selection:bg-secondary-container overflow-hidden">
         <!-- Elementos decorativos de fondo clínico -->
         <div class="absolute top-[-10%] right-[-5%] w-[40%] h-[40%] bg-secondary/5 rounded-full blur-[100px]
 pointer-events-none"></div>
         <div class="absolute bottom-[-10%] left-[-5%] w-[30%] h-[30%] bg-primary/5 rounded-full blur-[80px]
 pointer-events-none"></div>
    
         <div class="flex-grow flex flex-col items-center justify-center px-[20px] py-[32px] relative">
           <!-- Tarjeta de vidrio clinical -->
           <div class="glass-panel w-full max-w-[440px] p-[32px] rounded-lg animate-in fade-in zoom-in
 duration-700">
             
             <!-- Cabecera -->
             <div class="flex flex-col items-center text-center mb-[24px]">
               <span class="material-symbols-outlined text-[48px] text-primary mb-2">lock_reset</span>
               <h1 class="font-headline-lg text-headline-lg text-primary tracking-tight">Recuperar Contraseña</h1>
               <p class="font-body-sm text-body-sm text-on-surface-variant mt-[4px] max-w-[300px]">
                 Escriba su correo de registro y le enviaremos instrucciones de recuperación.
               </p>
             </div>
    
             <!-- Mensajes informativos -->
             <div v-if="message" class="mb-4 p-4 bg-green-50 text-green-800 border border-green-200 rounded-lg
 text-sm text-center font-medium animate-in fade-in duration-300">
               {{ message }}
             </div>
             <div v-if="error" class="mb-4 p-4 bg-red-50 text-red-800 border border-red-200 rounded-lg text-sm
 text-center font-medium animate-in fade-in duration-300">
               {{ error }}
             </div>
    
             <!-- Formulario -->
             <form v-if="!message" class="space-y-4" @submit.prevent="handleForgotPassword">
               <div class="space-y-1">
                 <label class="font-label-caps text-label-caps text-outline uppercase" for="email">
                   Correo electrónico
                 </label>
                 <div class="relative group">
                   <span class="material-symbols-outlined absolute left-[16px] top-1/2 -translate-y-1/2
 text-outline group-focus-within:text-primary transition-colors">mail</span>
                   <input
                     id="email"
                     v-model="email"
                     class="w-full pl-[44px] pr-[16px] py-[16px] bg-surface-container-lowest border
 border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none
 transition-all-custom text-body-lg"
                     placeholder="ejemplo@correo.com"
                     type="email"
                     autocomplete="email"
                     required
                   />
                 </div>
               </div>
    
               <!-- Botón de acción principal -->
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
                   Enviando enlace...
                 </span>
                 <span v-else class="flex items-center justify-center gap-1">
                   Enviar Instrucciones
                   <span class="material-symbols-outlined text-[18px]">send</span>
                 </span>
               </button>
             </form>
    
             <!-- Retorno al Login -->
             <div class="mt-[24px] pt-[24px] border-t border-outline-variant/30 text-center">
               <router-link to="/" class="font-button text-secondary hover:underline transition-all inline-flex
 items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                Volver al inicio de sesión
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
