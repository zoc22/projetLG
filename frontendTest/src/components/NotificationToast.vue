<template>
  <div class="fixed top-6 right-6 z-[200] flex flex-col gap-3 pointer-events-none">
    <transition-group name="notification">
      <div v-for="n in notifications" :key="n.id" 
        :class="[
          'pointer-events-auto px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4 min-w-[300px] border backdrop-blur-md animate-slideInRight',
          n.type === 'success' ? 'bg-green-50/90 border-green-200 text-green-800 dark:bg-green-900/90 dark:border-green-800 dark:text-green-100' : 
          n.type === 'error' ? 'bg-red-50/90 border-red-200 text-red-800 dark:bg-red-900/90 dark:border-red-800 dark:text-red-100' :
          'bg-blue-50/90 border-blue-200 text-blue-800 dark:bg-blue-900/90 dark:border-blue-800 dark:text-blue-100'
        ]"
      >
        <div :class="[
          'w-10 h-10 rounded-xl flex items-center justify-center shrink-0',
          n.type === 'success' ? 'bg-green-500 text-white' : 
          n.type === 'error' ? 'bg-red-500 text-white' :
          'bg-blue-500 text-white'
        ]">
          <i :class="[
            'fas',
            n.type === 'success' ? 'fa-check' : 
            n.type === 'error' ? 'fa-exclamation-circle' :
            'fa-info-circle'
          ]"></i>
        </div>
        <p class="font-bold text-sm">{{ n.message }}</p>
        <button @click="remove(n.id)" class="ml-auto text-current opacity-50 hover:opacity-100 transition-opacity">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </transition-group>
  </div>
</template>

<script setup>
import { useNotificationStore } from '@/stores/notificationStore'
import { storeToRefs } from 'pinia'

const notificationStore = useNotificationStore()
const { notifications } = storeToRefs(notificationStore)
const { remove } = notificationStore
</script>

<style scoped>
.notification-enter-active,
.notification-leave-active {
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.notification-enter-from {
  opacity: 0;
  transform: translateX(50px) scale(0.9);
}

.notification-leave-to {
  opacity: 0;
  transform: translateX(50px) scale(0.9);
}

@keyframes slideInRight {
  from { opacity: 0; transform: translateX(50px); }
  to { opacity: 1; transform: translateX(0); }
}
</style>
