<template>
  <div class="section-content flex items-center justify-center min-h-[60vh]">
    <div class="text-center p-12 bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 max-w-sm w-full">
      <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-400">
        <i class="fas fa-sign-out-alt text-3xl"></i>
      </div>
      <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Déconnexion</h2>
      <p class="text-gray-500 dark:text-gray-400 mt-2 mb-8">Êtes-vous sûr de vouloir vous déconnecter de votre session ?</p>
      
      <div class="space-y-3">
        <button @click="reconnect" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-2xl font-bold shadow-lg shadow-blue-500/20 transition-all">
          Oui, me déconnecter
        </button>
        <router-link to="/dashboard" class="block w-full bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 py-4 rounded-2xl font-bold transition-all">
          Annuler
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

const reconnect = () => {
  // Simuler déconnexion et retour
  alert('Déconnexion effectuée.')
  router.push('/dashboard')
}
</script>

<style scoped>
.section-content {
  animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes slideUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
