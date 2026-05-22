<template>
  <div class="section-content max-w-5xl mx-auto">
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-theme">Classement mondial</h1>
      <p class="text-theme-muted mt-2">Découvrez les leaders qui développent le plus rapidement leur réseau.</p>
    </div>

    <!-- Top 3 Podium -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12 items-end">
      <!-- 2nd Place -->
      <div class="order-2 md:order-1 flex flex-col items-center">
        <div class="w-20 h-20 rounded-full bg-gray-200 dark:bg-gray-700 border-4 border-white dark:border-gray-800 shadow-xl flex items-center justify-center text-gray-400 text-2xl font-bold relative mb-4">
          2
        </div>
        <div class="bg-[var(--card)] p-6 rounded-3xl shadow-sm border border-[var(--border)] w-full text-center h-40 flex flex-col justify-center">
          <p class="font-bold text-theme truncate px-2">{{ leaders[1]?.nom }}</p>
          <p class="text-2xl font-extrabold text-gray-400 mt-2">{{ leaders[1]?.membres }}</p>
          <p class="text-[10px] text-theme-muted uppercase font-bold tracking-widest">Membres</p>
        </div>
      </div>

      <!-- 1st Place -->
      <div class="order-1 md:order-2 flex flex-col items-center">
        <div class="w-24 h-24 rounded-full bg-yellow-400 border-4 border-white dark:border-gray-800 shadow-2xl flex items-center justify-center text-white text-3xl font-bold relative mb-4">
          <i class="fas fa-crown absolute -top-6 text-yellow-500 text-4xl drop-shadow-md"></i>
          1
        </div>
        <div class="bg-gradient-to-br from-yellow-400 to-orange-500 p-8 rounded-3xl shadow-xl shadow-yellow-500/20 w-full text-center h-48 flex flex-col justify-center text-white">
          <p class="font-bold text-lg truncate px-2">{{ leaders[0]?.nom }}</p>
          <p class="text-4xl font-black mt-2">{{ leaders[0]?.membres }}</p>
          <p class="text-[10px] text-yellow-100 uppercase font-bold tracking-widest">Champions de la semaine</p>
        </div>
      </div>

      <!-- 3rd Place -->
      <div class="order-3 md:order-3 flex flex-col items-center">
        <div class="w-16 h-16 rounded-full bg-orange-300 border-4 border-white dark:border-gray-800 shadow-xl flex items-center justify-center text-white text-xl font-bold relative mb-4">
          3
        </div>
        <div class="bg-[var(--card)] p-6 rounded-3xl shadow-sm border border-[var(--border)] w-full text-center h-36 flex flex-col justify-center">
          <p class="font-bold text-theme truncate px-2">{{ leaders[2]?.nom }}</p>
          <p class="text-2xl font-extrabold text-orange-400 mt-2">{{ leaders[2]?.membres }}</p>
          <p class="text-[10px] text-theme-muted uppercase font-bold tracking-widest">Membres</p>
        </div>
      </div>
    </div>

    <!-- Rest of the List -->
    <div class="bg-[var(--card)] rounded-3xl shadow-sm border border-[var(--border)] overflow-hidden">
      <div class="divide-y divide-[var(--border)]">
        <div v-for="(leader, idx) in leaders.slice(3)" :key="idx" class="flex items-center justify-between p-6 hover:bg-[var(--background)] transition-all">
          <div class="flex items-center gap-6">
            <span class="text-sm font-bold text-theme-muted w-6">{{ idx + 4 }}</span>
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full bg-[var(--background)] flex items-center justify-center text-theme-muted font-bold text-xs">
                {{ leader.nom.split(' ').map(n => n[0]).join('') }}
              </div>
              <span class="font-bold text-theme opacity-80">{{ leader.nom }}</span>
            </div>
          </div>
          <div class="text-right">
            <span class="text-lg font-extrabold text-theme">{{ leader.membres }}</span>
            <span class="text-[10px] text-theme-muted uppercase font-bold tracking-widest ml-2">Membres</span>
          </div>
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

<style scoped>
.section-content {
  animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes slideUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
