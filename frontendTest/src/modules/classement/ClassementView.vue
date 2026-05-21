<template>
  <div class="section-content">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">🏆 Classement des meilleurs recruteurs</h1>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
      <div class="space-y-1">
        <div v-for="(leader, idx) in leaders" :key="idx" :class="['flex justify-between p-2', idx === 0 ? 'bg-yellow-50 rounded-lg' : 'hover:bg-gray-50 rounded']">
          <span><i v-if="idx === 0" class="fas fa-crown text-yellow-500"></i> {{ idx+1 }}. {{ leader.nom }}</span>
          <span class="font-bold">{{ leader.membres }} membres</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { leadersData } from '@/data/staticData'
import { ref } from 'vue'

const leaders = ref(leadersData)
</script>
