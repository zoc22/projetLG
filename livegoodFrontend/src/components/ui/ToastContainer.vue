<template>
  <div class="fixed top-4 right-4 z-[100] flex flex-col gap-3 w-full max-w-sm pointer-events-none">
    <TransitionGroup
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="transform translate-x-full opacity-0"
      enter-to-class="transform translate-x-0 opacity-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="transform translate-x-0 opacity-100"
      leave-to-class="transform translate-x-full opacity-0"
    >
      <div
        v-for="notification in notifications"
        :key="notification.id"
        class="pointer-events-auto bg-white rounded-2xl shadow-2xl border-l-4 overflow-hidden flex"
        :class="{
          'border-red-500': notification.type === 'error',
          'border-green-500': notification.type === 'success',
          'border-blue-500': notification.type === 'info',
          'border-yellow-500': notification.type === 'warning',
        }"
      >
        <div class="p-4 flex-1">
          <div class="flex items-center gap-2 mb-1">
            <component
              :is="getIcon(notification.type)"
              class="h-5 w-5"
              :class="{
                'text-red-500': notification.type === 'error',
                'text-green-500': notification.type === 'success',
                'text-blue-500': notification.type === 'info',
                'text-yellow-500': notification.type === 'warning',
              }"
            />
            <h4 class="font-black text-slate-900 uppercase text-xs tracking-tight">
              {{ notification.title || getDefaultTitle(notification.type) }}
            </h4>
          </div>
          <p class="text-sm text-slate-600 font-medium">{{ notification.message }}</p>
        </div>
        <button
          @click="remove(notification.id)"
          class="p-4 text-slate-400 hover:text-slate-600 transition-colors"
        >
          <XIcon class="h-4 w-4" />
        </button>
      </div>
    </TransitionGroup>
  </div>
</template>

<script setup lang="ts">
import { 
  X as XIcon, 
  AlertCircle as ErrorIcon, 
  CheckCircle2 as SuccessIcon, 
  Info as InfoIcon, 
  AlertTriangle as WarningIcon 
} from 'lucide-vue-next';
import { useNotificationStore } from '../../core/stores/notificationStore';

const { notifications, remove } = useNotificationStore();

const getIcon = (type: string) => {
  switch (type) {
    case 'error': return ErrorIcon;
    case 'success': return SuccessIcon;
    case 'warning': return WarningIcon;
    default: return InfoIcon;
  }
};

const getDefaultTitle = (type: string) => {
  switch (type) {
    case 'error': return 'Erreur';
    case 'success': return 'Succès';
    case 'warning': return 'Attention';
    default: return 'Information';
  }
};
</script>
