<template>
  <div class="section-content max-w-6xl mx-auto">
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Mes recommandations</h1>
      <p class="text-gray-500 dark:text-gray-400 mt-2">Gérez vos partenaires directs et relancez les prospects inactifs.</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700">
        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Total Inscrits</p>
        <p class="text-3xl font-extrabold text-gray-900 dark:text-white">16</p>
      </div>
      <div class="bg-green-50 dark:bg-green-900/20 p-6 rounded-3xl border border-green-100 dark:border-green-800">
        <p class="text-[10px] text-green-600 dark:text-green-400 uppercase font-bold tracking-widest mb-1">Membres Actifs</p>
        <p class="text-3xl font-extrabold text-green-700 dark:text-green-400">10</p>
      </div>
      <div class="bg-red-50 dark:bg-red-900/20 p-6 rounded-3xl border border-red-100 dark:border-red-800">
        <p class="text-[10px] text-red-600 dark:text-red-400 uppercase font-bold tracking-widest mb-1">Inactifs</p>
        <p class="text-3xl font-extrabold text-red-700 dark:text-red-400">6</p>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left">
          <thead>
            <tr class="bg-gray-50 dark:bg-gray-700/50">
              <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Partenaire</th>
              <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Email</th>
              <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Statut</th>
              <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            <tr v-for="rec in recommendations" :key="rec.email" class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs">
                    {{ rec.nom.split(' ').map(n => n[0]).join('') }}
                  </div>
                  <span class="text-sm font-bold text-gray-900 dark:text-white">{{ rec.nom }}</span>
                </div>
              </td>
              <td class="px-6 py-4">
                <span class="text-sm text-gray-600 dark:text-gray-400">{{ rec.email }}</span>
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                  <div :class="['w-2 h-2 rounded-full', rec.status === 'Actif ✓' ? 'bg-green-500' : 'bg-red-500']"></div>
                  <span :class="['text-xs font-bold', rec.status === 'Actif ✓' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400']">
                    {{ rec.status === 'Actif ✓' ? 'Membre Actif' : 'Prospect Inactif' }}
                  </span>
                </div>
              </td>
              <td class="px-6 py-4 text-right">
                <div class="flex justify-end gap-2">
                  <button title="Envoyer un email" class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-blue-100 dark:hover:bg-blue-900/40 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-all flex items-center justify-center">
                    <i class="fas fa-envelope"></i>
                  </button>
                  <button v-if="rec.status !== 'Actif ✓'" title="Relancer" class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-[10px] font-bold uppercase tracking-wider transition-all">
                    Relancer
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { recommendationsData } from '@/data/staticData'
import { ref } from 'vue'

const recommendations = ref(recommendationsData)
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
