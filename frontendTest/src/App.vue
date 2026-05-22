<template>
  <div :class="['flex h-screen w-full bg-[var(--background)] text-[var(--foreground)] transition-colors duration-300', darkMode ? 'dark' : '']">
    <!-- Overlay pour mobile -->
    <div 
      v-if="isSidebarOpen" 
      @click="setSidebarOpen(false)" 
      class="fixed inset-0 bg-black/50 z-30 lg:hidden transition-opacity"
    ></div>

    <div class="flex h-screen w-full overflow-hidden">
      <AppSidebar />

      <!-- ========== MAIN CONTENT ========== -->
      <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <AppHeader />

        <main class="flex-1 overflow-y-auto p-4 md:p-6 scrollbar-custom">
          <router-view v-slot="{ Component }">
            <transition name="fade" mode="out-in">
              <component :is="Component" />
            </transition>
          </router-view>
        </main>
      </div>
    </div>
    <NotificationToast />
  </div>
</template>

<script setup>
import { useSidebar } from '@/composables/useSidebar'
import { useTheme } from '@/composables/useTheme'

// Layouts
import AppSidebar from '@/layouts/AppSidebar.vue'
import AppHeader from '@/layouts/AppHeader.vue'
import NotificationToast from '@/components/NotificationToast.vue'

const { isSidebarOpen, setSidebarOpen } = useSidebar()
const { darkMode } = useTheme()
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css');

* { font-family: 'Inter', sans-serif; }

.sidebar-item {
  transition: all 0.2s ease;
  border-left: 3px solid transparent;
}
.sidebar-item:hover { 
  background-color: #f3f4f6; 
  border-left-color: #3b82f6; 
}
.dark .sidebar-item:hover { 
  background-color: #374151; 
}

.sidebar-item.active {
  background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
  border-left-color: #3b82f6;
  color: #1d4ed8;
  font-weight: 600;
}
.dark .sidebar-item.active {
  background: linear-gradient(135deg, #1e3a5f 0%, #1f2937 100%);
  color: #60a5fa;
}

.scrollbar-custom::-webkit-scrollbar { width: 5px; }
.scrollbar-custom::-webkit-scrollbar-track { background: transparent; }
.scrollbar-custom::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
.dark .scrollbar-custom::-webkit-scrollbar-thumb { background: #4b5563; }

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.section-content { animation: slideIn 0.3s ease-out; }
@keyframes slideIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

/* Marketplace specific animations */
@keyframes scrollText {
  0% { transform: translateX(100%); }
  100% { transform: translateX(-100%); }
}
.animate-scroll {
  display: inline-block;
  white-space: nowrap;
  animation: scrollText 20s linear infinite;
}

@keyframes scrollProducts {
  0% { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}
.animate-scroll-products {
  display: flex;
  gap: 1.5rem;
  animation: scrollProducts 30s linear infinite;
  width: max-content;
}
.animate-scroll-products:hover {
  animation-play-state: paused;
}
</style>
