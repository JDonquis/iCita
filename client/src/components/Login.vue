<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const router = useRouter();
const authStore = useAuthStore();

const form = ref({
  email: '',
  password: '',
});
const error = ref('');

const handleLogin = async () => {
  error.value = '';
  try {
    await authStore.login(form.value);
    router.push('/dashboard');
  } catch (e) {
    error.value = 'Credenciales inválidas o error de conexión.';
  }
};
</script>

<template>
  <!-- Fondo con gradiente clínico -->
  <div class="bg-gradient-clinical min-h-screen flex flex-col font-body-lg text-on-surface selection:bg-secondary-container overflow-hidden">

    <!-- Elementos decorativos de fondo -->
    <div class="absolute top-[-10%] right-[-5%] w-[40%] h-[40%] bg-secondary/5 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-[-10%] left-[-5%] w-[30%] h-[30%] bg-primary/5 rounded-full blur-[80px] pointer-events-none"></div>

    <!-- ======================== -->
    <!--  VISTA DESKTOP (lg+)     -->
    <!-- ======================== -->
    <main class="hidden lg:flex flex-grow items-center justify-center px-[20px] py-[32px] relative">
      <!-- Tarjeta de login central -->
      <div class="glass-panel w-full max-w-[440px] p-[32px] rounded-lg animate-in fade-in zoom-in duration-700">

        <!-- Sección de marca -->
        <div class="flex flex-col items-center text-center mb-[24px]">
          <div class="w-16 h-16 mb-[16px] drop-shadow-sm">
            <img
              alt="Logo"
              class="w-full h-full object-contain"
              src=""
            />
          </div>
          <h1 class="font-headline-lg text-headline-lg text-primary tracking-tight">iCita Medical</h1>
          <p class="font-body-sm text-body-sm text-on-surface-variant mt-[4px] max-w-[280px]">
            Bienvenido a su portal médico. Por favor, inicie sesión.
          </p>
        </div>

        <!-- Sección del formulario -->
        <form class="space-y-4" @submit.prevent="handleLogin">

          <!-- Campo Email -->
          <div class="space-y-1">
            <label class="font-label-caps text-label-caps text-outline uppercase" for="email-desktop">
              Correo electrónico
            </label>
            <div class="relative group">
              <span class="material-symbols-outlined absolute left-[16px] top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">mail</span>
              <input
                id="email-desktop"
                v-model="form.email"
                class="w-full pl-[44px] pr-[16px] py-[16px] bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all-custom text-body-lg"
                placeholder="ejemplo@correo.com"
                type="email"
                autocomplete="email"
                required
              />
            </div>
          </div>

          <!-- Campo Contraseña -->
          <div class="space-y-1">
            <div class="flex justify-between items-center">
              <label class="font-label-caps text-label-caps text-outline uppercase" for="password-desktop">
                Contraseña
              </label>
              <router-link 
                  class="text-label-caps font-label-caps text-secondary hover:text-primary transition-colors" 
                  to="/forgot-password"
                >
                  ¿Olvidó su contraseña?
                </router-link>
            </div>
            <div class="relative group">
              <span class="material-symbols-outlined absolute left-[16px] top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">lock</span>
              <input
                id="password-desktop"
                v-model="form.password"
                class="w-full pl-[44px] pr-[16px] py-[16px] bg-surface-container-lowest border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all-custom text-body-lg"
                placeholder="••••••••"
                type="password"
                autocomplete="current-password"
                required
              />
            </div>
          </div>

          <!-- Mensaje de error -->
          <p v-if="error" class="text-error text-body-sm font-body-sm">{{ error }}</p>

          <!-- Botón de envío -->
          <button
            class="w-full py-[16px] bg-primary-container text-on-primary hover:bg-primary transition-all-custom font-button text-button rounded-lg shadow-sm active:scale-[0.98] mt-[24px]"
            type="submit"
            :disabled="authStore.loading"
          >
            <span v-if="authStore.loading" class="flex items-center justify-center gap-2">
              <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Cargando...
            </span>
            <span v-else>Iniciar Sesión</span>
          </button>
        </form>

        <!-- Enlaces inferiores -->
        <div class="mt-[24px] pt-[24px] border-t border-outline-variant/30 text-center">
          <p class="font-body-sm text-body-sm text-on-surface-variant">
            ¿No tiene una cuenta?
            <a class="font-button text-secondary hover:underline ml-[4px] transition-all" href="#">Crear Cuenta</a>
          </p>
        </div>
      </div>
    </main>

    <!-- Footer desktop -->
    <footer class="hidden lg:flex w-full py-[16px] justify-center items-center bg-surface/30 backdrop-blur-sm">
      <button class="flex items-center gap-[4px] font-label-caps text-label-caps text-on-surface-variant hover:text-primary transition-colors group">
        <span class="material-symbols-outlined text-[18px] group-hover:animate-pulse">support_agent</span>
        Contactar Soporte Técnico
      </button>
    </footer>


    <!-- ============================ -->
    <!--  VISTA MÓVIL (hasta lg)      -->
    <!-- ============================ -->
    <div class="lg:hidden flex flex-col items-center justify-center min-h-screen px-[20px] py-[32px]">

      <!-- Cabecera -->
      <div class="w-full max-w-sm flex flex-col items-center mb-[32px] animate-in fade-in slide-in-from-top-4 duration-700">
        <div class="w-24 h-24 mb-[16px] flex items-center justify-center">
          <img
            alt="Logo"
            class="w-full h-full object-contain"
            src=""
          />
        </div>
        <div class="text-center space-y-2">
          <h1 class="font-headline-lg text-headline-lg text-primary tracking-tight">iCita Medical</h1>
          <p class="font-body-sm text-body-sm text-on-surface-variant max-w-[280px]">
            Bienvenido a su portal médico. Por favor, inicie sesión.
          </p>
        </div>
      </div>

      <!-- Formulario móvil -->
      <main class="w-full max-w-sm glass-container rounded-xl p-[24px] animate-in fade-in zoom-in-95 duration-500">
        <form class="space-y-6" @submit.prevent="handleLogin">

          <!-- Email móvil -->
          <div class="space-y-1">
            <label class="block font-label-caps text-label-caps text-on-surface-variant" for="email-mobile">
              CORREO ELECTRÓNICO
            </label>
            <div class="relative focused-input border border-outline-variant rounded-md bg-surface-container-lowest">
              <span class="material-symbols-outlined absolute left-[16px] top-1/2 -translate-y-1/2 text-primary" style="font-size:20px">mail</span>
              <input
                id="email-mobile"
                v-model="form.email"
                class="w-full pl-[48px] pr-[16px] py-[16px] bg-transparent border-none outline-none font-body-lg text-body-lg text-on-surface placeholder:text-outline-variant rounded-md"
                placeholder="ejemplo@icita.com"
                type="email"
                autocomplete="email"
                required
              />
            </div>
          </div>

          <!-- Contraseña móvil -->
          <div class="space-y-1">
            <div class="flex justify-between items-center">
              <label class="font-label-caps text-label-caps text-on-surface-variant" for="password-mobile">
                CONTRASEÑA
              </label>
              <a class="font-label-caps text-label-caps text-primary hover:underline" href="#">
                ¿Olvidó su contraseña?
              </a>
            </div>
            <div class="relative focused-input border border-outline-variant rounded-md bg-surface-container-lowest">
              <span class="material-symbols-outlined absolute left-[16px] top-1/2 -translate-y-1/2 text-primary" style="font-size:20px">lock</span>
              <input
                id="password-mobile"
                v-model="form.password"
                class="w-full pl-[48px] pr-[16px] py-[16px] bg-transparent border-none outline-none font-body-lg text-body-lg text-on-surface placeholder:text-outline-variant rounded-md"
                placeholder="••••••••"
                type="password"
                autocomplete="current-password"
                required
              />
            </div>
          </div>

          <!-- Mensaje de error móvil -->
          <p v-if="error" class="text-error text-body-sm font-body-sm">{{ error }}</p>

          <!-- Botón móvil -->
          <button
            class="w-full bg-primary-container text-on-primary py-[16px] rounded-md font-button text-button shadow-lg hover:bg-primary transition-all btn-hover flex items-center justify-center gap-2"
            type="submit"
            :disabled="authStore.loading"
          >
            <span v-if="authStore.loading" class="flex items-center justify-center gap-2">
              <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Cargando...
            </span>
            <template v-else>
              Iniciar Sesión
              <span class="material-symbols-outlined" style="font-size:20px">login</span>
            </template>
          </button>
        </form>
      </main>

      <!-- Footer móvil -->
      <footer class="mt-[32px] w-full max-w-sm flex flex-col items-center gap-[16px] text-center animate-in fade-in duration-1000">
        <p class="font-body-sm text-body-sm text-on-surface-variant">
          ¿Nuevo en iCita?
          <a class="text-primary font-bold hover:underline ml-[4px]" href="#">Crear Cuenta</a>
        </p>
        <div class="h-px w-16 bg-outline-variant/30"></div>
        <a class="flex items-center gap-[4px] font-label-caps text-label-caps text-outline hover:text-primary transition-colors" href="#">
          <span class="material-symbols-outlined" style="font-size:16px">support_agent</span>
          Contactar Soporte Técnico
        </a>
      </footer>
    </div>

  </div>
</template>

<style>
/* ---- Panel de vidrio (desktop) ---- */
.glass-panel {
  background: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(0, 102, 136, 0.3);
  box-shadow: 0px 4px 20px rgba(0, 102, 136, 0.1);
}

/* ---- Contenedor de vidrio (móvil) ---- */
.glass-container {
  background: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(0, 119, 189, 0.3);
  box-shadow: 0 4px 20px rgba(0, 94, 150, 0.1);
}

/* ---- Gradiente de fondo clínico ---- */
.bg-gradient-clinical {
  background: linear-gradient(135deg, #f7f9fb 0%, #e3f2fd 50%, #f7f9fb 100%);
}

/* ---- Input con foco (móvil) ---- */
.focused-input:focus-within {
  border-color: #58cafe;
  box-shadow: 0 0 0 4px rgba(88, 202, 254, 0.15);
}

/* ---- Transición suave personalizada ---- */
.transition-all-custom {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ---- Micro-interacción botón ---- */
.btn-hover:active {
  transform: scale(0.98);
  transition: transform 0.1s ease;
}
</style>