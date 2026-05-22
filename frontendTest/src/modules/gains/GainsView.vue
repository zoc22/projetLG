<template>
  <div class="section-content max-w-6xl mx-auto">
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Mes gains</h1>
      <p class="text-gray-500 dark:text-gray-400 mt-2">Suivez vos commissions et configurez votre mode de retrait.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Summary Cards -->
      <div class="lg:col-span-2 space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
          <div class="flex items-center justify-between mb-8">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Récapitulatif des commissions</h3>
            <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[10px] font-bold rounded-lg uppercase tracking-wider">Mois en cours</span>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div v-for="gain in gainBreakdown" :key="gain.type" class="p-6 bg-gray-50 dark:bg-gray-700/50 rounded-2xl flex items-center justify-between border border-transparent hover:border-blue-200 dark:hover:border-blue-800 transition-all">
              <div>
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">{{ gain.type }}</p>
                <p class="text-xl font-extrabold text-gray-800 dark:text-white mt-1">{{ gain.amount }}</p>
              </div>
              <div :class="['w-10 h-10 rounded-xl flex items-center justify-center', gain.bg]">
                <i :class="[gain.icon, gain.color]"></i>
              </div>
            </div>
          </div>

          <div class="mt-8 p-6 bg-blue-600 rounded-2xl text-white flex items-center justify-between shadow-xl shadow-blue-500/20">
            <div>
              <p class="text-blue-100 text-xs font-bold uppercase tracking-widest">Total Gains accumulés</p>
              <p class="text-3xl font-black mt-1">1,247.00$</p>
            </div>
            <button class="bg-white text-blue-700 px-6 py-3 rounded-xl font-bold text-sm hover:bg-blue-50 transition-all">
              Retirer mes fonds
            </button>
          </div>
        </div>

        <div class="bg-amber-50 dark:bg-amber-900/20 rounded-3xl p-6 border border-amber-100 dark:border-amber-800 flex items-start gap-4">
          <div class="w-12 h-12 bg-white dark:bg-gray-800 rounded-xl flex items-center justify-center shadow-sm shrink-0 text-amber-600">
            <i class="fas fa-clock text-xl"></i>
          </div>
          <div>
            <p class="font-bold text-amber-900 dark:text-amber-200">Note sur les paiements</p>
            <p class="text-amber-800 dark:text-amber-400 text-sm mt-1 leading-relaxed">
              Il y a un décalage d'une semaine pour les commissions hebdomadaires (Fast Start). Les commissions de matrice et les matching bonus sont payés mensuellement la première semaine du mois suivant.
            </p>
          </div>
        </div>
      </div>

      <!-- Settings Side -->
      <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
          <h4 class="font-bold text-gray-900 dark:text-white mb-6">Mode de retrait</h4>
          <div class="space-y-4">
            <div class="p-4 rounded-2xl bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 flex items-center gap-4">
              <div class="w-10 h-10 bg-white dark:bg-gray-800 rounded-xl flex items-center justify-center text-blue-600 shadow-sm">
                <i class="fab fa-bitcoin"></i>
              </div>
              <div class="flex-1">
                <p class="text-xs font-bold text-gray-900 dark:text-white">Portefeuille Crypto</p>
                <p class="text-[10px] text-gray-500 dark:text-gray-400">Configuré (BitPay)</p>
              </div>
              <i class="fas fa-check-circle text-green-500"></i>
            </div>
            <button class="w-full py-3 border border-gray-200 dark:border-gray-700 rounded-2xl text-xs font-bold text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
              Modifier mes options de paiement
            </button>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
          <h4 class="font-bold text-gray-900 dark:text-white mb-4">Prochain jour de paie</h4>
          <div class="text-center p-6 bg-gray-50 dark:bg-gray-700/50 rounded-2xl">
            <p class="text-3xl font-black text-blue-600 dark:text-blue-400">Vendredi</p>
            <p class="text-sm font-bold text-gray-800 dark:text-gray-200 mt-1">3 Mars 2025</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const gainBreakdown = [
  { type: 'Fast Start Bonus', amount: '625.50$', icon: 'fas fa-bolt', color: 'text-blue-600', bg: 'bg-blue-100 dark:bg-blue-900/30' },
  { type: 'Matrix Bonus', amount: '421.50$', icon: 'fas fa-sitemap', color: 'text-green-600', bg: 'bg-green-100 dark:bg-green-900/30' },
  { type: 'Matching Bonus', amount: '200.00$', icon: 'fas fa-copy', color: 'text-purple-600', bg: 'bg-purple-100 dark:bg-purple-900/30' },
  { type: 'Retail / Influenceur', amount: '0.00$', icon: 'fas fa-tag', color: 'text-orange-600', bg: 'bg-orange-100 dark:bg-orange-900/30' }
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
