<template>
  <header class="bg-[var(--card)] border-b border-[var(--border)] p-3 flex justify-between items-center transition-colors">
    <div class="flex items-center gap-3">
      <button @click="setSidebarOpen(true)" class="lg:hidden text-[var(--foreground)] opacity-70">
        <i class="fas fa-bars text-xl"></i>
      </button>
      <button @click="toggleSidebarDesktop" class="hidden lg:flex items-center gap-2 text-sm text-theme opacity-70 hover:opacity-100 transition-opacity">
        <i :class="[isSidebarDesktopOpen ? 'fas fa-chevron-left' : 'fas fa-chevron-right']"></i>
        <span v-if="!isSidebarDesktopOpen">Menu</span>
      </button>
      <span class="font-bold text-xl text-theme lg:hidden">Live<span class="text-blue-600">Good</span></span>
    </div>
    <div class="flex items-center gap-4">
      <!-- Sélecteur de langue -->
      <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-[var(--background)] rounded-xl border border-[var(--border)]">
        <i class="fas fa-language text-theme opacity-40"></i>
        <select class="bg-transparent text-xs font-bold text-theme outline-none">
          <option>Français</option>
          <option>English</option>
          <option>Español</option>
        </select>
      </div>

      <button @click="toggleDarkMode" class="p-2 rounded-lg bg-[var(--background)] hover:bg-gray-200 dark:hover:bg-gray-700 transition">
        <i v-if="!darkMode" class="fas fa-moon text-gray-600"></i>
        <i v-else class="fas fa-sun text-yellow-500"></i>
      </button>
    </div>
  </header>
</template>

<script setup>
import { useSidebar } from '@/composables/useSidebar'
import { useTheme } from '@/composables/useTheme'

const { isSidebarDesktopOpen, toggleSidebarDesktop, setSidebarOpen } = useSidebar()
const { darkMode, toggleDarkMode } = useTheme()
</script>
