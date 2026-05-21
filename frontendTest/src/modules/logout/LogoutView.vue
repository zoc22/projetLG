<template>
  <div class="section-content">
    <div class="text-center py-16">
      <i class="fas fa-sign-out-alt text-5xl text-gray-400 mb-4"></i>
      <h2 class="text-xl font-bold text-gray-800">Vous êtes déconnecté</h2>
      <button @click="reconnect" class="mt-5 bg-blue-600 text-white px-6 py-2 rounded-lg">Reconnecter</button>
    </div>
  </div>
</template>

<script setup>
import { useUIStore } from '@/stores/uiStore'

const uiStore = useUIStore()

const reconnect = () => {
  uiStore.setActiveSection('dashboard')
}
</script>
