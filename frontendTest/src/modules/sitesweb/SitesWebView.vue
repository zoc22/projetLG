<template>
  <div class="section-content max-w-6xl mx-auto">
    <div class="mb-10">
      <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Mes sites web</h1>
      <p class="text-gray-500 dark:text-gray-400 mt-2">Utilisez ces liens stratégiques pour développer votre entreprise en fonction de votre cible.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <SiteCard v-for="site in sitesWeb" :key="site.type" :site="site" />
    </div>

    <!-- Strategy Tip -->
    <div class="mt-12 p-8 bg-blue-50 dark:bg-blue-900/20 rounded-3xl border border-blue-100 dark:border-blue-800 flex items-start gap-6">
      <div class="w-14 h-14 bg-white dark:bg-gray-800 rounded-2xl flex items-center justify-center text-blue-600 shadow-sm shrink-0">
        <i class="fas fa-lightbulb text-2xl"></i>
      </div>
      <div>
        <h4 class="text-lg font-bold text-gray-900 dark:text-white">Conseil stratégique</h4>
        <p class="text-gray-600 dark:text-gray-400 mt-2 leading-relaxed">
          Pour une efficacité maximale, utilisez le <strong>Site Inscription</strong> pour vos campagnes publicitaires et réseaux sociaux. Il est conçu pour convertir les visiteurs en pré-inscrits rapidement grâce à la vidéo de présentation.
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import SiteCard from './components/SiteCard.vue'
import { sitesWebData } from '@/data/staticData'
import { ref } from 'vue'

const sitesWeb = ref(sitesWebData)
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
