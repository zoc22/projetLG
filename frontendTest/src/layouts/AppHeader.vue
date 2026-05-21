<template>
  <header class="bg-white border-b border-gray-200 p-3 flex justify-between items-center">
    <div class="flex items-center gap-3">
      <button @click="setSidebarOpen(true)" class="lg:hidden text-gray-600">
        <i class="fas fa-bars text-xl"></i>
      </button>
      <button @click="toggleSidebarDesktop" class="hidden lg:flex items-center gap-2 text-sm text-gray-600 hover:text-gray-800">
        <i :class="[isSidebarDesktopOpen ? 'fas fa-chevron-left' : 'fas fa-chevron-right']"></i>
        <span v-if="!isSidebarDesktopOpen">Menu</span>
      </button>
      <span class="font-bold text-xl text-gray-800 lg:hidden">Live<span class="text-blue-600">Good</span></span>
    </div>
    <div class="flex items-center gap-3">
      <span class="text-sm text-gray-600 hidden lg:block">{{ darkMode ? 'Mode clair' : 'Mode sombre' }}</span>
      <button @click="toggleDarkMode" class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition">
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
