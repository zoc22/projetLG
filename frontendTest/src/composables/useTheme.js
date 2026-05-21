import { storeToRefs } from 'pinia'
import { useUIStore } from '@/stores/uiStore'
import { onMounted } from 'vue'

export function useTheme() {
  const uiStore = useUIStore()
  const { darkMode } = storeToRefs(uiStore)

  const toggleDarkMode = () => {
    uiStore.toggleDarkMode()
  }

  onMounted(() => {
    uiStore.updateTheme()
  })

  return {
    darkMode,
    toggleDarkMode
  }
}
