import { storeToRefs } from 'pinia'
import { useUIStore } from '@/stores/uiStore'

export function useSidebar() {
  const uiStore = useUIStore()
  const { isSidebarOpen, isSidebarDesktopOpen } = storeToRefs(uiStore)

  const toggleSidebarDesktop = () => {
    uiStore.toggleSidebarDesktop()
  }

  const setSidebarOpen = (value) => {
    uiStore.setSidebarOpen(value)
  }

  return {
    isSidebarOpen,
    isSidebarDesktopOpen,
    toggleSidebarDesktop,
    setSidebarOpen
  }
}
