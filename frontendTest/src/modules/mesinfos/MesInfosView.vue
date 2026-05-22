<template>
  <div class="section-content max-w-4xl mx-auto">
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Mes informations</h1>
      <p class="text-gray-500 dark:text-gray-400 mt-2">Gérez vos coordonnées personnelles et vos paramètres de compte.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <!-- Profile Card -->
      <div class="md:col-span-2 space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
            <i class="fas fa-user-circle text-blue-500"></i> Informations personnelles
          </h3>
          
          <div class="grid md:grid-cols-2 gap-6">
            <div v-for="(val, key) in userInfo" :key="key" class="space-y-1">
              <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">{{ labels[key] }}</p>
              <p class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ val }}</p>
            </div>
          </div>

          <div class="mt-8 pt-8 border-t border-gray-100 dark:border-gray-700 flex justify-end">
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl font-semibold transition-all shadow-md shadow-blue-500/10">
              Modifier mes informations
            </button>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
            <i class="fas fa-credit-card text-indigo-500"></i> Mode de paiement par défaut
          </h3>
          
          <div class="flex items-center gap-4 p-5 bg-gray-50 dark:bg-gray-700/50 rounded-2xl border border-gray-100 dark:border-gray-600">
            <div class="w-12 h-12 bg-white dark:bg-gray-800 rounded-xl flex items-center justify-center shadow-sm">
              <i class="fab fa-cc-visa text-blue-600 text-2xl"></i>
            </div>
            <div class="flex-1">
              <p class="font-bold text-gray-800 dark:text-white">Visa se terminant par 4242</p>
              <p class="text-xs text-gray-500 dark:text-gray-400 italic">Expire le 12/2026</p>
            </div>
            <button class="text-blue-600 dark:text-blue-400 font-bold text-sm">Changer</button>
          </div>
        </div>
      </div>

      <!-- Side Column: Account Security -->
      <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
          <h4 class="font-bold text-gray-900 dark:text-white mb-4">Sécurité</h4>
          <div class="space-y-4">
            <button class="w-full flex items-center justify-between p-4 rounded-2xl bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-600 transition-all group">
              <div class="flex items-center gap-3">
                <i class="fas fa-lock text-gray-400 group-hover:text-blue-500 transition-colors"></i>
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Mot de passe</span>
              </div>
              <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
            </button>
            <button class="w-full flex items-center justify-between p-4 rounded-2xl bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-600 transition-all group">
              <div class="flex items-center gap-3">
                <i class="fas fa-shield-alt text-gray-400 group-hover:text-green-500 transition-colors"></i>
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Double Auth (2FA)</span>
              </div>
              <span class="px-2 py-0.5 bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400 text-[10px] font-bold rounded">ACTIF</span>
            </button>
          </div>
        </div>

        <div class="bg-red-50 dark:bg-red-900/10 rounded-3xl p-6 border border-red-100 dark:border-red-900/30">
          <h4 class="font-bold text-red-800 dark:text-red-400 mb-2">Zone de danger</h4>
          <p class="text-xs text-red-700/70 dark:text-red-400/60 mb-4">La suppression de votre compte est irréversible et entraînera la perte de votre place dans la matrice.</p>
          <button class="text-red-600 dark:text-red-400 text-xs font-bold hover:underline">Résilier mon adhésion</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const labels = {
  fullName: 'Nom complet',
  email: 'Adresse email',
  phone: 'Téléphone',
  country: 'Pays',
  city: 'Ville',
  zip: 'Code Postal'
}

const userInfo = {
  fullName: 'Yohann Zoc',
  email: 'yohann.zoc@gmail.com',
  phone: '+33 6 12 34 56 78',
  country: 'France',
  city: 'Paris',
  zip: '75001'
}
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
