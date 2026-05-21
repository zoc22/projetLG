<template>
  <div class="section-content">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">Mes recommandations</h1>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 overflow-x-auto">
      <table class="w-full text-left">
        <thead class="bg-gray-50">
          <tr>
            <th class="p-3 text-gray-700">Nom</th>
            <th>Email</th>
            <th>Statut</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="rec in recommendations" :key="rec.nom" class="border-b">
            <td class="p-3">{{ rec.nom }}</td>
            <td>{{ rec.email }}</td>
            <td :class="rec.statusClass">{{ rec.status }}</td>
            <td><i class="fas fa-envelope text-gray-400 cursor-pointer"></i></td>
          </tr>
        </tbody>
      </table>
      <div class="mt-4 p-3 bg-amber-50 rounded-lg text-sm"><i class="fas fa-info-circle"></i> 10 membres actifs / 16 total</div>
    </div>
  </div>
</template>

<script setup>
import { recommendationsData } from '@/data/staticData'
import { ref } from 'vue'

const recommendations = ref(recommendationsData)
</script>
