<template>
  <div class="section-content">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">Horaire des webinaires</h1>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
      <div class="space-y-3">
        <div v-for="web in webinaires" :key="web.jour" class="flex justify-between items-center p-3 border-b">
          <span><i :class="[web.icon, 'text-' + web.iconColor + '-500']"></i> {{ web.jour }} {{ web.horaire }} ({{ web.langue }})</span>
          <span :class="web.titleClass">{{ web.titre }}</span>
        </div>
      </div>
      <div class="mt-5 p-3 bg-blue-50 rounded-lg text-sm"><i class="fas fa-language"></i> Sous-titres : Français, Anglais, Espagnol, Allemand.</div>
    </div>
  </div>
</template>

<script setup>
import { webinairesData } from '@/data/staticData'
import { ref } from 'vue'

const webinaires = ref(webinairesData)
</script>
