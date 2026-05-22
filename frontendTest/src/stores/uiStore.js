import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useUIStore = defineStore('ui', () => {
  const darkMode = ref(localStorage.getItem('darkMode') === 'true')
  const isSidebarOpen = ref(false)
  const isSidebarDesktopOpen = ref(localStorage.getItem('sidebarDesktopOpen') !== 'false')

  const toggleDarkMode = () => {
    darkMode.value = !darkMode.value
    localStorage.setItem('darkMode', darkMode.value)
    updateTheme()
  }

  const updateTheme = () => {
    if (darkMode.value) {
      document.documentElement.classList.add('dark')
    } else {
      document.documentElement.classList.remove('dark')
    }
  }

  const toggleSidebarDesktop = () => {
    isSidebarDesktopOpen.value = !isSidebarDesktopOpen.value
    localStorage.setItem('sidebarDesktopOpen', isSidebarDesktopOpen.value)
  }

  const setSidebarOpen = (value) => {
    isSidebarOpen.value = value
  }

  return {
    darkMode,
    isSidebarOpen,
    isSidebarDesktopOpen,
    toggleDarkMode,
    updateTheme,
    toggleSidebarDesktop,
    setSidebarOpen
  }
})
