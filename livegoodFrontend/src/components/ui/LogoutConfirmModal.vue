<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center">
    <!-- Backdrop -->
    <div 
      class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
      @click="onCancel"
    ></div>

    <!-- Modal -->
    <div class="relative bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 animate-in fade-in zoom-in-95 duration-200">
      <!-- Close Button -->
      <button 
        @click="onCancel"
        class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition-colors"
      >
        <XIcon :size="24" />
      </button>

      <!-- Header -->
      <div class="mb-6">
        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 mb-4">
          <AlertCircleIcon class="text-red-600" :size="24" />
        </div>
        <h2 class="text-2xl font-bold text-slate-900">Confirmer la déconnexion</h2>
      </div>

      <!-- Message -->
      <p class="text-slate-600 mb-8">
        Êtes-vous sûr de vouloir vous déconnecter ? Vous devrez vous authentifier à nouveau pour accéder à votre compte.
      </p>

      <!-- Actions -->
      <div class="flex gap-3">
        <button
          @click="onCancel"
          class="flex-1 px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl transition-colors"
        >
          Annuler
        </button>
        <button
          @click="onConfirm"
          :disabled="isLoading"
          class="flex-1 px-4 py-3 bg-red-600 hover:bg-red-700 disabled:bg-red-500 text-white font-medium rounded-xl transition-colors flex items-center justify-center gap-2"
        >
          <LogOutIcon v-if="!isLoading" :size="18" />
          <span v-if="isLoading" class="inline-block animate-spin">⏳</span>
          {{ isLoading ? 'Déconnexion...' : 'Déconnecter' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { defineProps, defineEmits } from 'vue';
import { 
  X as XIcon,
  AlertCircle as AlertCircleIcon,
  LogOut as LogOutIcon
} from 'lucide-vue-next';

defineProps({
  isOpen: {
    type: Boolean,
    required: true
  },
  isLoading: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['confirm', 'cancel']);

const onConfirm = () => {
  emit('confirm');
};

const onCancel = () => {
  emit('cancel');
};
</script>
