import { ref } from 'vue'
import { recentUsersData } from '@/data/staticData'

export function useDashboardData() {
  const recentUsers = ref(recentUsersData)

  return {
    recentUsers
  }
}
