<template>
  <div class="section-content max-w-7xl mx-auto">
    <div class="mb-10">
      <h1 class="text-3xl font-bold text-theme">Tableau de bord</h1>
      <p class="text-theme-muted mt-2">Vue d'ensemble de votre activité et des performances de votre réseau.</p>
    </div>
    
    <!-- Cartes KPI -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
      <KPICard 
        title="Total membres" 
        value="128" 
        icon="fas fa-users" 
        bgColor="bg-blue-100 dark:bg-blue-900/30" 
        iconColor="text-blue-600 dark:text-blue-400" 
        trendText="+12 cette semaine" 
        trendColor="text-green-600 dark:text-green-400" 
        trendIcon="fas fa-arrow-up" 
      />
      <KPICard 
        title="Membres actifs" 
        value="96" 
        icon="fas fa-check-circle" 
        bgColor="bg-green-100 dark:bg-green-900/30" 
        iconColor="text-green-600 dark:text-green-400" 
        trendText="32 inactifs à relancer" 
        trendColor="text-amber-600 dark:text-amber-400" 
      />
      <KPICard 
        title="Pré-inscrits" 
        value="45" 
        icon="fas fa-hourglass-half" 
        bgColor="bg-purple-100 dark:bg-purple-900/30" 
        iconColor="text-purple-600 dark:text-purple-400" 
        trendText="Mois en cours" 
        trendColor="text-theme-muted" 
      />
      <KPICard 
        title="Gains (7j)" 
        value="1,247.00$" 
        icon="fas fa-dollar-sign" 
        bgColor="bg-amber-100 dark:bg-amber-900/30" 
        iconColor="text-amber-600 dark:text-amber-400" 
        trendText="Décalage paiement +7j" 
        trendColor="text-theme-muted" 
      />
    </div>
    
    <div class="grid lg:grid-cols-3 gap-8">
      <div class="lg:col-span-2">
        <EarningsChart />
      </div>
      <div class="lg:col-span-1">
        <RecentPreinscriptions :recentUsers="recentUsers" />
      </div>
    </div>
  </div>
</template>

<script setup>
import KPICard from './components/KPICard.vue'
import EarningsChart from './components/EarningsChart.vue'
import RecentPreinscriptions from './components/RecentPreinscriptions.vue'
import { useDashboardData } from './composables/useDashboardData'

const { recentUsers } = useDashboardData()
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
