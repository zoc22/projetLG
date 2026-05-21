<template>
  <div class="section-content">
    <div class="mb-6">
      <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Tableau de bord</h1>
      <p class="text-gray-500 mt-1">Bienvenue Yohann ! Voici l'activité de votre réseau.</p>
    </div>
    
    <!-- Cartes KPI -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      <KPICard 
        title="Total membres" 
        value="128" 
        icon="fas fa-users" 
        bgColor="bg-blue-100" 
        iconColor="text-blue-600" 
        trendText="+12 cette semaine" 
        trendColor="text-green-600" 
        trendIcon="fas fa-arrow-up" 
      />
      <KPICard 
        title="Membres actifs" 
        value="96" 
        icon="fas fa-check-circle" 
        bgColor="bg-green-100" 
        iconColor="text-green-600" 
        trendText="32 inactifs à relancer" 
        trendColor="text-amber-600" 
      />
      <KPICard 
        title="Pré-inscrits (mois)" 
        value="45" 
        icon="fas fa-hourglass-half" 
        bgColor="bg-purple-100" 
        iconColor="text-purple-600" 
        trendText="En attente de paiement" 
        trendColor="text-gray-500" 
      />
      <KPICard 
        title="Gains (semaine)" 
        value="1,247€" 
        icon="fas fa-euro-sign" 
        bgColor="bg-amber-100" 
        iconColor="text-amber-600" 
        trendText="Décalage paiement +7j" 
        trendColor="text-gray-500" 
      />
    </div>
    
    <div class="grid lg:grid-cols-2 gap-6">
      <EarningsChart />
      <RecentPreinscriptions :recentUsers="recentUsers" />
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
