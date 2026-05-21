<template>
  <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
    <h3 class="font-bold text-gray-800 mb-4">Évolution des gains</h3>
    <div style="height: 280px; position: relative;">
      <canvas id="earningsChart"></canvas>
    </div>
  </div>
</template>

<script setup>
import { onMounted, watch, nextTick } from 'vue'
import { useChart } from '@/composables/useChart'
import { useUIStore } from '@/stores/uiStore'

const { initChart } = useChart()
const uiStore = useUIStore()

const refreshChart = () => {
  nextTick(() => {
    setTimeout(() => {
      initChart('earningsChart')
    }, 300)
  })
}

onMounted(() => {
  refreshChart()
})

// Réinitialiser le graphique lors du changement de thème, de l'état du sidebar ou de la section active
watch(() => uiStore.darkMode, refreshChart)
watch(() => uiStore.isSidebarDesktopOpen, refreshChart)
watch(() => uiStore.activeSection, (newSection) => {
  if (newSection === 'dashboard') {
    refreshChart()
  }
})

// Gérer le redimensionnement de la fenêtre
window.addEventListener('resize', () => {
  initChart('earningsChart')
})
</script>
