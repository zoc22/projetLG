<template>
  <div class="section-content">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">Statistiques avancées</h1>
    <div class="grid md:grid-cols-3 gap-5">
      <div v-for="stat in statsSites" :key="stat.nom" class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <i :class="[stat.icon, 'text-2xl mb-2']"></i>
        <p class="text-gray-500 text-sm">{{ stat.nom }}</p>
        <p class="text-2xl font-bold text-gray-800">{{ stat.valeur }}</p>
        <p class="text-sm" :class="stat.trendClass">{{ stat.trend }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { statsSitesData } from '@/data/staticData'
import { ref } from 'vue'

const statsSites = ref(statsSitesData)
</script>
