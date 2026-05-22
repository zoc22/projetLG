<template>
  <aside :class="[
    'fixed lg:relative inset-y-0 left-0 w-80 bg-[var(--card)] shadow-2xl border-r border-[var(--border)] flex flex-col z-40 overflow-y-auto scrollbar-custom transition-all duration-300 ease-in-out',
    isSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
    !isSidebarDesktopOpen && 'lg:w-20 lg:translate-x-0'
  ]">
    <!-- Logo + Toggle Theme + Toggle Sidebar -->
    <div class="p-4 border-b border-[var(--border)]">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3" :class="{ 'lg:justify-center lg:w-full': !isSidebarDesktopOpen }">
          <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center shadow-lg shrink-0">
            <i class="fas fa-crown text-white text-lg"></i>
          </div>
          <div v-show="isSidebarDesktopOpen" class="transition-opacity" :class="{ 'lg:hidden': !isSidebarDesktopOpen }">
            <span class="font-bold text-xl text-[var(--foreground)]">Live<span class="text-blue-600">Good</span></span>
            <p class="text-[10px] text-[var(--foreground)] opacity-50 mt-0.5">Plateforme Affilié Premium</p>
          </div>
        </div>
        <div class="flex gap-2">
          <button @click="toggleSidebarDesktop" class="hidden lg:flex w-8 h-8 rounded-full bg-[var(--background)] hover:opacity-80 items-center justify-center transition shrink-0">
            <i :class="[isSidebarDesktopOpen ? 'fas fa-chevron-left' : 'fas fa-chevron-right', 'text-[var(--foreground)] opacity-70 text-sm']"></i>
          </button>
          <button @click="toggleDarkMode" class="w-8 h-8 rounded-full bg-[var(--background)] hover:opacity-80 flex items-center justify-center transition shrink-0">
            <i v-if="!darkMode" class="fas fa-moon text-gray-600"></i>
            <i v-else class="fas fa-sun text-yellow-500"></i>
          </button>
        </div>
      </div>
    </div>
    
    <!-- Profil utilisateur -->
    <div class="p-4 border-b border-[var(--border)] bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10" :class="{ 'lg:p-2': !isSidebarDesktopOpen }">
      <div class="flex items-center gap-3" :class="{ 'lg:justify-center': !isSidebarDesktopOpen }">
        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-md shrink-0">
          <span class="text-white font-bold">YZ</span>
        </div>
        <div v-show="isSidebarDesktopOpen" class="flex-1 min-w-0 transition-opacity">
          <p class="font-bold text-[var(--foreground)] text-base truncate">Yohann Zoc</p>
          <p class="text-xs text-blue-600 dark:text-blue-400 font-medium">Affilié Bronze</p>
          <p class="text-[10px] text-[var(--foreground)] opacity-50 mt-0.5"><i class="fas fa-map-marker-alt"></i> France</p>
        </div>
      </div>
    </div>
    
    <!-- Navigation menu -->
    <nav class="flex-1 py-4 px-3 space-y-1">
      <button v-for="item in menuItems" :key="item.id" @click="navigateTo(item.id)"
        :class="['sidebar-item w-full text-left px-3 py-2 rounded-xl flex items-center gap-3 transition-all', route.name === item.id ? 'active' : 'text-[var(--foreground)] opacity-70 hover:bg-[var(--background)] hover:opacity-100', !isSidebarDesktopOpen && 'lg:justify-center']">
        <i :class="[item.icon, 'w-5 text-center shrink-0']"></i>
        <span v-show="isSidebarDesktopOpen" class="transition-opacity">{{ item.label }}</span>
      </button>
    </nav>
    
    <!-- Date limite -->
    <div class="p-3 border-t border-[var(--border)] bg-red-50 dark:bg-red-900/10 m-3 rounded-xl" :class="{ 'lg:p-2': !isSidebarDesktopOpen }">
      <div class="flex items-center gap-2 mb-1" :class="{ 'lg:justify-center': !isSidebarDesktopOpen }">
        <i class="fas fa-hourglass-half text-red-500 shrink-0"></i>
        <span v-show="isSidebarDesktopOpen" class="text-[10px] font-bold text-red-600 dark:text-red-400">PROCHAINE DATE LIMITE</span>
      </div>
      <p v-show="isSidebarDesktopOpen" class="text-xs font-bold text-[var(--foreground)]">jeu. 2-mars-2025</p>
      <button class="mt-2 w-full bg-red-500 hover:bg-red-600 text-white py-1.5 rounded-lg text-xs font-semibold transition">
        REGARDER
      </button>
    </div>
  </aside>
</template>

<script setup>
import { useRouter, useRoute } from 'vue-router'
import { menuItems } from '@/data/staticData'
import { useSidebar } from '@/composables/useSidebar'
import { useTheme } from '@/composables/useTheme'

const router = useRouter()
const route = useRoute()
const { isSidebarOpen, isSidebarDesktopOpen, toggleSidebarDesktop, setSidebarOpen } = useSidebar()
const { darkMode, toggleDarkMode } = useTheme()

const navigateTo = (sectionId) => {
  router.push({ name: sectionId }).catch(err => {
    console.error('Navigation error:', err)
  })
  if (window.innerWidth < 1024) {
    setSidebarOpen(false)
  }
}
</script>
