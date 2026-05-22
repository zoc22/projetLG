<template>
  <div class="bg-[var(--card)] rounded-3xl p-8 shadow-sm border border-[var(--border)]">
    <div class="flex items-center justify-between mb-8">
      <h3 class="text-xl font-black text-theme uppercase tracking-tight">Analyse des Commissions</h3>
      <div class="flex gap-2">
        <div class="flex items-center gap-2 px-3 py-1 bg-green-50 dark:bg-green-900/20 rounded-full border border-green-100 dark:border-green-800">
          <div class="w-2 h-2 rounded-full bg-green-500"></div>
          <span class="text-[10px] font-black text-green-600 dark:text-green-400 uppercase">+15.2%</span>
        </div>
      </div>
    </div>
    <div style="height: 320px; position: relative;">
      <canvas id="earningsChart"></canvas>
    </div>
  </div>
</template>

<script setup>
import { onMounted, watch, nextTick } from 'vue'
import { useRoute } from 'vue-router'
import { useChart } from '@/composables/useChart'
import { useUIStore } from '@/stores/uiStore'

const { initChart } = useChart()
const uiStore = useUIStore()
const route = useRoute()

let chartTimeout = null

const refreshChart = () => {
  if (chartTimeout) clearTimeout(chartTimeout)
  nextTick(() => {
    chartTimeout = setTimeout(() => {
      initChart('earningsChart')
    }, 300)
  })
}

onMounted(() => {
  refreshChart()
})

// Réinitialiser le graphique lors du changement de thème, de l'état du sidebar ou de la route
watch(() => uiStore.darkMode, refreshChart)
watch(() => uiStore.isSidebarDesktopOpen, refreshChart)
watch(() => route.path, (newPath) => {
  if (newPath === '/dashboard') {
    refreshChart()
  }
})

// Gérer le redimensionnement de la fenêtre
window.addEventListener('resize', () => {
  initChart('earningsChart')
})
</script>
