<template>
  <div class="section-content max-w-6xl mx-auto pb-20">
    <div class="mb-10 text-center">
      <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight">Plan de Rémunération</h1>
      <p class="text-gray-500 dark:text-gray-400 mt-4 max-w-2xl mx-auto">LiveGood partage ses revenus avec ses affiliés à travers un système innovant et généreux. Voici les 6 façons d'être payé.</p>
    </div>

    <!-- The 6 Bonuses Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <!-- 1. Fast Start -->
      <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-all group">
        <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center text-blue-600 dark:text-blue-400 mb-6 group-hover:scale-110 transition-transform">
          <i class="fas fa-bolt text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Fast Start Bonus</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
          Gagnez 50% de commission sur chaque nouveau membre que vous parrainez. Payé chaque semaine.
        </p>
        <div class="mt-6 pt-6 border-t border-gray-50 dark:border-gray-700 flex items-center justify-between">
          <span class="text-sm font-bold text-blue-600 dark:text-blue-400">25$ / inscription</span>
          <i class="fas fa-arrow-right text-gray-300"></i>
        </div>
      </div>

      <!-- 2. Matrix Bonus -->
      <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-all group">
        <div class="w-14 h-14 bg-green-100 dark:bg-green-900/30 rounded-2xl flex items-center justify-center text-green-600 dark:text-green-400 mb-6 group-hover:scale-110 transition-transform">
          <i class="fas fa-sitemap text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Bonus de Matrice</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
          Gagnez 2.5% sur les adhésions mensuelles de toute votre matrice 2x15. Jusqu'à 16,383$.
        </p>
        <div class="mt-6 pt-6 border-t border-gray-50 dark:border-gray-700 flex items-center justify-between">
          <span class="text-sm font-bold text-green-600 dark:text-green-400">0.25$ / membre / mois</span>
          <i class="fas fa-arrow-right text-gray-300"></i>
        </div>
      </div>

      <!-- 3. Matching Bonus -->
      <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-all group">
        <div class="w-14 h-14 bg-purple-100 dark:bg-purple-900/30 rounded-2xl flex items-center justify-center text-purple-600 dark:text-purple-400 mb-6 group-hover:scale-110 transition-transform">
          <i class="fas fa-copy text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Matching Bonus</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
          Gagnez 50% des gains de matrice de vos affiliés directs et sur 5 générations.
        </p>
        <div class="mt-6 pt-6 border-t border-gray-50 dark:border-gray-700 flex items-center justify-between">
          <span class="text-sm font-bold text-purple-600 dark:text-purple-400">50% du chèque filleul</span>
          <i class="fas fa-arrow-right text-gray-300"></i>
        </div>
      </div>

      <!-- 4. Retail Commissions -->
      <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-all group">
        <div class="w-14 h-14 bg-orange-100 dark:bg-orange-900/30 rounded-2xl flex items-center justify-center text-orange-600 dark:text-orange-400 mb-6 group-hover:scale-110 transition-transform">
          <i class="fas fa-tag text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Vente au détail</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
          Gagnez 50% de la différence entre le prix membre et le prix public.
        </p>
        <div class="mt-6 pt-6 border-t border-gray-50 dark:border-gray-700 flex items-center justify-between">
          <span class="text-sm font-bold text-orange-600 dark:text-orange-400">50% de la marge</span>
          <i class="fas fa-arrow-right text-gray-300"></i>
        </div>
      </div>

      <!-- 5. Influencer Bonus -->
      <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-all group">
        <div class="w-14 h-14 bg-pink-100 dark:bg-pink-900/30 rounded-2xl flex items-center justify-center text-pink-600 dark:text-pink-400 mb-6 group-hover:scale-110 transition-transform">
          <i class="fas fa-users-rays text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Bonus Influenceur</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
          Bonus supplémentaires pour les gros volumes de ventes au détail mensuels.
        </p>
        <div class="mt-6 pt-6 border-t border-gray-50 dark:border-gray-700 flex items-center justify-between">
          <span class="text-sm font-bold text-pink-600 dark:text-pink-400">Jusqu'à 100% de la marge</span>
          <i class="fas fa-arrow-right text-gray-300"></i>
        </div>
      </div>

      <!-- 6. Diamond Pool -->
      <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-all group">
        <div class="w-14 h-14 bg-cyan-100 dark:bg-cyan-900/30 rounded-2xl flex items-center justify-center text-cyan-600 dark:text-cyan-400 mb-6 group-hover:scale-110 transition-transform">
          <i class="fas fa-gem text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Diamond Bonus Pool</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
          Partagez 2% du chiffre d'affaires mondial total de l'entreprise chaque mois.
        </p>
        <div class="mt-6 pt-6 border-t border-gray-50 dark:border-gray-700 flex items-center justify-between">
          <span class="text-sm font-bold text-cyan-600 dark:text-cyan-400">2% du CA Mondial</span>
          <i class="fas fa-arrow-right text-gray-300"></i>
        </div>
      </div>
    </div>

    <!-- Qualification Ranks Section -->
    <div class="mt-20">
      <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-10 text-center">Rangs de Qualification</h2>
      <div class="space-y-4">
        <div v-for="rank in rankDetails" :key="rank.name" class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col md:flex-row md:items-center gap-6">
          <div :class="['w-16 h-16 rounded-xl flex items-center justify-center text-white text-2xl shadow-lg', rank.bg]">
            <i :class="rank.icon"></i>
          </div>
          <div class="flex-1">
            <h4 class="text-xl font-bold text-gray-900 dark:text-white">{{ rank.name }}</h4>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ rank.req }}</p>
          </div>
          <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-2 rounded-xl text-center">
            <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Plafond Matrice</p>
            <p class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ rank.cap }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const rankDetails = [
  { name: 'Bronze', icon: 'fas fa-shield-halved', bg: 'bg-orange-500', req: '2 membres actifs inscrits personnellement.', cap: '4,095.50$' },
  { name: 'Argent', icon: 'fas fa-award', bg: 'bg-gray-400', req: '10 directs ou 3 Bronze dans des branches différentes + 20 membres actifs.', cap: '4,095.50$' },
  { name: 'Or', icon: 'fas fa-medal', bg: 'bg-yellow-500', req: '30 directs ou 3 Argent dans des branches différentes + 100 membres actifs.', cap: '8,191.50$' },
  { name: 'Platine', icon: 'fas fa-trophy', bg: 'bg-indigo-500', req: '60 directs ou 3 Or dans des branches différentes + 500 membres actifs.', cap: '8,191.50$' },
  { name: 'Diamant', icon: 'fas fa-gem', bg: 'bg-blue-500', req: '3 Platine dans 3 branches différentes + 2,500 membres actifs.', cap: '16,383.50$' }
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
