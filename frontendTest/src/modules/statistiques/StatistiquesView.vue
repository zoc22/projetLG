<template>
  <div class="section-content max-w-6xl mx-auto">
    <div class="mb-10">
      <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Statistiques de trafic</h1>
      <p class="text-gray-500 dark:text-gray-400 mt-2">Analysez les performances de vos liens et le taux de conversion de vos prospects.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
      <div v-for="stat in statsSites" :key="stat.nom" class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition-all">
        <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-gray-50 dark:bg-gray-700 mb-6">
          <i :class="[stat.icon, 'text-xl']"></i>
        </div>
        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">{{ stat.nom }}</p>
        <p class="text-3xl font-black text-gray-900 dark:text-white">{{ stat.valeur }}</p>
        <div class="mt-4 flex items-center gap-2">
          <span :class="['text-xs font-bold px-2 py-0.5 rounded-lg', stat.trendClass.includes('green') ? 'bg-green-100 dark:bg-green-900/30 text-green-600' : 'bg-amber-100 dark:bg-amber-900/30 text-amber-600']">
            {{ stat.trend }}
          </span>
          <span class="text-[10px] text-gray-400 font-medium">vs mois dernier</span>
        </div>
      </div>
    </div>

    <!-- Chart Placeholder -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
      <div class="flex items-center justify-between mb-10">
        <h4 class="text-xl font-bold text-gray-900 dark:text-white">Visites quotidiennes</h4>
        <div class="flex gap-2">
          <button class="px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-lg text-xs font-bold">7 jours</button>
          <button class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs font-bold">30 jours</button>
        </div>
      </div>
      
      <div class="h-64 flex items-end gap-2 px-2">
        <div v-for="n in 30" :key="n" :style="{ height: Math.random() * 100 + '%' }" class="flex-1 bg-blue-500/20 dark:bg-blue-400/20 rounded-t-sm hover:bg-blue-500 transition-colors relative group">
          <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 text-white text-[10px] rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10">
            {{ Math.floor(Math.random() * 50) + 10 }} visites
          </div>
        </div>
      </div>
      <div class="flex justify-between mt-4 text-[10px] text-gray-400 font-bold uppercase tracking-widest px-2">
        <span>1 {{ currentMonth }}</span>
        <span>15 {{ currentMonth }}</span>
        <span>30 {{ currentMonth }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { statsSitesData } from '@/data/staticData'
import { ref } from 'vue'

const statsSites = ref(statsSitesData)
const currentMonth = 'Mars'
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
