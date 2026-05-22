<template>
  <div class="section-content max-w-5xl mx-auto">
    <div class="mb-10">
      <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Horaire des webinaires</h1>
      <p class="text-gray-500 dark:text-gray-400 mt-2">Restez informé et formez-vous avec les leaders et les co-fondateurs de LiveGood.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <!-- Weekly Schedule -->
      <div class="space-y-4">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Planning hebdomadaire</h3>
        <div v-for="web in webinaires" :key="web.jour" class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:border-blue-200 dark:hover:border-blue-800 transition-all flex items-center gap-4 group">
          <div :class="['w-12 h-12 rounded-xl flex items-center justify-center text-xl shadow-sm', web.jour === 'Jeudi' ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-400']">
            <i class="fas fa-calendar-day"></i>
          </div>
          <div class="flex-1">
            <div class="flex items-center gap-2">
              <span class="font-bold text-gray-900 dark:text-white">{{ web.jour }}</span>
              <span class="text-xs px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-lg font-bold">{{ web.horaire }}</span>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ web.titre }}</p>
          </div>
          <div class="text-right">
            <span class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-widest">{{ web.langue }}</span>
          </div>
        </div>
      </div>

      <!-- LiveGood TV / Special Events -->
      <div class="space-y-6">
        <div class="bg-gradient-to-br from-indigo-600 to-blue-700 p-8 rounded-3xl text-white shadow-xl shadow-blue-500/20">
          <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-2xl mb-6">
            <i class="fas fa-video"></i>
          </div>
          <h4 class="text-xl font-bold">LiveGood Zoom Direct</h4>
          <p class="text-blue-100 text-sm mt-2 leading-relaxed">
            Chaque jeudi soir avec les co-fondateurs pour les mises à jour majeures de l'entreprise.
          </p>
          <button class="mt-6 w-full bg-white text-blue-700 py-3 rounded-2xl font-bold hover:bg-blue-50 transition-all">
            Rejoindre le Zoom
          </button>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700">
          <h4 class="font-bold text-gray-900 dark:text-white mb-4">Note sur les sous-titres</h4>
          <div class="flex items-start gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-2xl border border-gray-100 dark:border-gray-600">
            <i class="fas fa-closed-captioning text-blue-500 text-xl mt-1"></i>
            <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
              Les webinaires en anglais disposent d'une traduction automatique en direct. Allez dans <strong>Préférences > Sous-titres > Traduire automatiquement</strong> et choisissez votre langue.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { webinairesData } from '@/data/staticData'
import { ref } from 'vue'

const webinaires = ref(webinairesData)
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
