<template>
  <div class="section-content max-w-6xl mx-auto">
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Mon équipe</h1>
      <p class="text-gray-500 dark:text-gray-400 mt-2">Visualisez votre organisation et l'évolution de vos partenaires.</p>
    </div>

    <!-- Rank Legend -->
    <div class="flex flex-wrap gap-3 mb-8">
      <div v-for="rank in ranks" :key="rank.name" class="flex items-center gap-2 px-3 py-1.5 bg-white dark:bg-gray-800 rounded-full border border-gray-100 dark:border-gray-700 shadow-sm">
        <div :class="['w-3 h-3 rounded-full', rank.color]"></div>
        <span class="text-[10px] font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">{{ rank.name }}</span>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden">
      <!-- Matrix Stats -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10 text-center">
        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-2xl">
          <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Génération 1</p>
          <p class="text-2xl font-extrabold text-gray-900 dark:text-white">2 / 2</p>
        </div>
        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-2xl">
          <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Génération 2</p>
          <p class="text-2xl font-extrabold text-gray-900 dark:text-white">4 / 4</p>
        </div>
        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-2xl">
          <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Génération 3</p>
          <p class="text-2xl font-extrabold text-gray-900 dark:text-white">8 / 8</p>
        </div>
        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-2xl">
          <p class="text-[10px] text-blue-600 dark:text-blue-400 uppercase font-bold tracking-widest">Total Matrice</p>
          <p class="text-2xl font-extrabold text-blue-700 dark:text-blue-300">128</p>
        </div>
      </div>

      <!-- Matrix Tree Visualization (Simplified for UI) -->
      <div class="flex flex-col items-center py-10">
        <!-- Root -->
        <div class="relative mb-16">
          <div class="w-16 h-16 rounded-full bg-orange-400 border-4 border-white dark:border-gray-800 shadow-xl flex items-center justify-center text-white font-bold text-xl relative z-10">
            YZ
          </div>
          <div class="absolute -bottom-8 left-1/2 -translate-x-1/2 whitespace-nowrap">
            <p class="text-xs font-bold text-gray-900 dark:text-white">Yohann Zoc</p>
            <p class="text-[10px] text-orange-500 font-bold uppercase text-center">Bronze</p>
          </div>
          <!-- Lines to children -->
          <div class="absolute top-1/2 left-1/2 w-48 h-1 bg-gray-200 dark:bg-gray-700 -translate-x-1/2 -z-0"></div>
          <div class="absolute top-1/2 left-0 w-1 h-12 bg-gray-200 dark:bg-gray-700 -z-0"></div>
          <div class="absolute top-1/2 right-0 w-1 h-12 bg-gray-200 dark:bg-gray-700 -z-0"></div>
        </div>

        <!-- Level 1 -->
        <div class="flex gap-32">
          <div class="relative">
            <div class="w-12 h-12 rounded-full bg-gray-400 border-4 border-white dark:border-gray-800 shadow-lg flex items-center justify-center text-white font-bold relative z-10">
              ML
            </div>
            <div class="absolute -bottom-8 left-1/2 -translate-x-1/2 whitespace-nowrap text-center">
              <p class="text-[10px] font-bold text-gray-700 dark:text-gray-300">Marc Laurent</p>
              <p class="text-[8px] text-gray-400 font-bold uppercase">Non classé</p>
            </div>
          </div>
          <div class="relative">
            <div class="w-12 h-12 rounded-full bg-orange-400 border-4 border-white dark:border-gray-800 shadow-lg flex items-center justify-center text-white font-bold relative z-10">
              SB
            </div>
            <div class="absolute -bottom-8 left-1/2 -translate-x-1/2 whitespace-nowrap text-center">
              <p class="text-[10px] font-bold text-gray-700 dark:text-gray-300">Sophie Bernard</p>
              <p class="text-[8px] text-orange-500 font-bold uppercase">Bronze</p>
            </div>
          </div>
        </div>

        <button class="mt-24 px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold shadow-lg shadow-blue-500/20 transition-all flex items-center gap-2">
          <i class="fas fa-search-plus"></i> Explorer la matrice complète (2x15)
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
const ranks = [
  { name: 'Non classé', color: 'bg-gray-400' },
  { name: 'Bronze', color: 'bg-orange-400' },
  { name: 'Argent', color: 'bg-gray-300' },
  { name: 'Or', color: 'bg-yellow-400' },
  { name: 'Platine', color: 'bg-indigo-300' },
  { name: 'Diamant', color: 'bg-blue-300' }
]
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
