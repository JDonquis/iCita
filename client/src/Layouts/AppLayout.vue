<script setup>
import { useAuthStore } from '../stores/auth';

    const authStore = useAuthStore();

    const props = defineProps({
        titulo: {
            type: String,
            required: true
        },
        subtitulo: {
            type: String,
            required: true
        }
    });
</script>

<template>
  <div class="bg-surface font-body-lg text-on-surface min-h-screen w-full overflow-hidden flex selection:bg-secondary-container">
    
    <!-- ============================================ -->
    <!-- DESKTOP SIDENAVBAR (lg+)                      -->
    <!-- ============================================ -->
    <aside class="hidden lg:flex flex-col h-screen py-8 px-6 bg-surface/70 backdrop-blur-md w-72 border-r border-secondary/30 shadow-[0px_4px_20px_rgba(0,102,136,0.1)] z-40 shrink-0">
      
      <!-- Logo y título -->
      <div class="flex flex-col items-center justify-center text-center gap-3 mb-10 mt-2">
        <div class="w-14 h-14 object-contain rounded-2xl bg-primary/10 flex items-center justify-center shadow-sm border border-primary/20">
          <span class="material-symbols-outlined text-primary text-3xl">local_hospital</span>
        </div>
        <div>
          <h1 class="text-2xl font-bold text-primary tracking-tight">iCita Pro</h1>
          <p class="text-xs font-bold text-on-surface-variant/70 uppercase tracking-widest mt-1">Health Management</p>
        </div>
      </div>
      
      <!-- Menú de navegación menu only for desktop-->
      <nav class="flex-1 space-y-2">
        <!-- Dashboard - Activo -->
        <router-link 
          to="/dashboard"
          class="flex items-center gap-4 px-6 py-3 rounded-xl transition-all active:scale-95 text-primary font-bold bg-primary/10 border border-primary/20 shadow-sm"         >
          <span class="material-symbols-outlined">dashboard</span>
          <span class="text-base">Dashboard</span>
        </router-link>
        
        <a class="flex items-center gap-4 px-6 py-3 rounded-xl transition-colors hover:bg-surface-variant/50 text-on-surface-variant hover:text-primary" href="#">
          <span class="material-symbols-outlined">event</span>
          <span class="text-base">Citas</span>
        </a>
        
        <router-link 
          to="/personnel"
          class="flex items-center gap-4 px-6 py-3 rounded-xl transition-colors hover:bg-surface-variant/50 text-on-surface-variant hover:text-primary"        >
          <span class="material-symbols-outlined">person</span>
          <span class="text-base">Personas</span>
        </router-link>
        
        <a class="flex items-center gap-4 px-6 py-3 rounded-xl transition-colors hover:bg-surface-variant/50 text-on-surface-variant hover:text-primary" href="#">
          <span class="material-symbols-outlined">person</span>
          <span class="text-base">Pacientes</span>
        </a>

        <a class="flex items-center gap-4 px-6 py-3 rounded-xl transition-colors hover:bg-surface-variant/50 text-on-surface-variant hover:text-primary" href="#">
          <span class="material-symbols-outlined">history_edu</span>
          <span class="text-base">Historias Medicas</span>
        </a>
        
        
        <a class="flex items-center gap-4 px-6 py-3 rounded-xl transition-colors hover:bg-surface-variant/50 text-on-surface-variant hover:text-primary" href="#">
          <span class="material-symbols-outlined">settings</span>
          <span class="text-base">Configuraciones</span>
        </a>
      </nav>
      
      <!-- Footer del sidebar -->
      <div class="mt-auto pt-4 border-t border-outline-variant/30">
      </div>
    </aside>
    
    <!-- ============================================ -->
    <!-- MAIN CONTENT WRAPPER                         -->
    <!-- ============================================ -->
    <div class="flex-1 flex flex-col h-screen w-full relative">
      
      <!-- ========================================== -->
      <!-- TOPNAVBAR DESKTOP (lg+)                     -->
      <!-- ========================================== -->
      <header class="hidden lg:flex justify-between items-center px-lg w-full sticky top-0 z-50 bg-surface/70 backdrop-blur-md h-16 border-b border-secondary/30 shadow-sm shrink-0">
        <!-- Buscador / Título -->
        <div class="flex items-center gap-lg flex-1">
          <div>
            <h2 class="text-headline-lg px-6 font-headline-lg text-primary">{{ props.titulo }}</h2>
          </div>
        </div>
        
        <!-- Acciones del usuario -->
        <div class="flex items-center gap-md">
          <div class="h-8 w-[1px] bg-secondary/20 mx-sm"></div>
          
          <!-- Perfil del usuario -->
          <div class="flex items-center gap-sm cursor-pointer hover:bg-surface-variant/30 p-1 rounded-lg transition-colors">
            <div class="text-right">
              <p class="text-body-sm font-semibold leading-tight">{{ authStore.user?.person?.full_name || 'Usuario' }}</p>
              <p class="text-label-caps text-on-surface-variant/70 uppercase">{{ authStore.user?.role || 'Admin' }}</p>
            </div>
            <div class="w-10 h-10 rounded-full border-2 border-primary/20 bg-primary-container text-on-primary flex justify-center items-center font-bold">
              {{ authStore.user?.person?.full_name?.charAt(0) || 'U' }}
            </div>
          </div>
        </div>
      </header>
      
      <!-- ============================================ -->
      <!-- TOP APP BAR MOBILE (hasta lg)                 -->
      <!-- ============================================ -->
      <header class="lg:hidden fixed top-0 w-full z-50 glass-panel border-b border-secondary/30 shadow-[0_4px_20px_rgba(0,94,150,0.1)] h-[84px] flex items-center shrink-0">
        <div class="flex justify-between items-center px-[20px] w-full">
          <!-- Logo / Título móvil -->
          <div class="flex items-center gap-md">
            <div>
              <h1 class="font-headline-md text-headline-md text-primary font-bold">iCita Pro</h1>
              <p class="font-body-sm px-6 text-body-sm text-on-surface-variant font-bold">{{ props.titulo }}</p>
            </div>
          </div>
          
          <!-- Acciones -->
          <div class="flex items-center gap-md">
            <button class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-primary/10 transition-colors active:scale-95">
              <span class="material-symbols-outlined text-primary">settings</span>
            </button>
          </div>
        </div>
      </header>

      <!-- ========================================== -->
      <!-- MAIN PAGE CONTENT (SLOT)                   -->
      <!-- ========================================== -->
      <main class="flex-1 overflow-y-auto w-full">
        <!-- Espaciador móvil top -->
        <div class="lg:hidden h-[100px]"></div> 
        
        <div class="flex items-end justify-between">
            <div>
                <p v-if="props.subtitulo != ''" class="text-body-lg px-5 text-on-surface-variant">{{ props.subtitulo }}</p>
                <p v-else class="text-body-lg px-5 text-on-surface-variant">&nbsp;</p>
            </div>
        </div>    

        <!-- Contenido Inyectado -->
        <div class="p-lg lg:p-xl  padin space-y-lg px-[20px] lg:px-lg max-w-full">
            <slot></slot>
        </div>
        
        <!-- Espaciador móvil bottom -->
        <div class="lg:hidden h-[100px]"></div>
      </main>
      
      <!-- ============================================ -->
      <!-- BOTTOM NAVIGATION BAR MOBILE (hasta lg)  (menu only for mobile)     -->
      <!-- ============================================ -->
      <nav class="lg:hidden fixed bottom-0 w-full z-50 glass-panel rounded-t-xl border-t border-secondary/30 shadow-[0_-4px_20px_rgba(0,94,150,0.1)] safe-area-bottom pb-2">
        <div class="flex justify-around items-center w-full px-xs py-sm">
          
          <!-- Dashboard (Activo) -->
          <router-link to="/dashboard" class="flex flex-col items-center justify-center text-primary font-bold transition-transform active:scale-90">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">dashboard</span>
            <span class="font-label-caps text-label-caps">Inicio</span>
          </router-link>
          
          <a class="flex flex-col items-center justify-center text-outline transition-transform active:scale-90" href="#">
            <span class="material-symbols-outlined">event</span>
            <span class="font-label-caps text-label-caps">Citas</span>
          </a>
          
          <a class="flex flex-col items-center justify-center text-outline transition-transform active:scale-90" href="#">
            <span class="material-symbols-outlined">person</span>
            <span class="font-label-caps text-label-caps">Personas</span>
          </a>
          
          <a class="flex flex-col items-center justify-center text-outline transition-transform active:scale-90" href="#">
            <span class="material-symbols-outlined">person</span>
            <span class="font-label-caps text-label-caps">Paientes</span>
          </a>

          <a class="flex flex-col items-center justify-center text-outline transition-transform active:scale-90" href="#">
            <span class="material-symbols-outlined">history_edu</span>
            <span class="font-label-caps text-label-caps">Historias</span>
          </a>

          <a class="flex flex-col items-center justify-center text-outline transition-transform active:scale-90" href="#">
            <span class="material-symbols-outlined">settings</span>
            <span class="font-label-caps text-label-caps">Ajustes</span>
          </a>


        </div>
      </nav>
      
    </div>
  </div>
</template>

<style scoped>
.glass-panel {
  background: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(0, 94, 150, 0.15);
}

.glass-card {
  background: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(0, 102, 136, 0.15);
  transition: all 0.3s ease;
}

.glass-card:hover {
  transform: translateY(-2px);
  box-shadow: 0px 10px 30px rgba(0, 102, 136, 0.15);
}

.safe-area-bottom {
  padding-bottom: env(safe-area-inset-bottom);
}
</style>
