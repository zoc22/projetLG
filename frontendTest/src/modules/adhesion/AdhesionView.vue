<template>
  <div class="section-content max-w-4xl mx-auto">
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-theme">Mon adhésion</h1>
      <p class="text-theme-muted mt-2">Gérez votre abonnement LiveGood et accédez aux avantages exclusifs des membres.</p>
    </div>

    <div class="grid gap-6">
      <!-- Current Status Card -->
      <div class="bg-[var(--card)] rounded-3xl p-8 shadow-sm border border-[var(--border)] relative overflow-hidden">
        <div class="absolute top-0 right-0 p-8 opacity-5 dark:opacity-10 text-theme">
          <i class="fas fa-id-card text-9xl"></i>
        </div>
        
        <div class="relative z-10">
          <div class="flex items-center gap-4 mb-8">
            <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center text-blue-600 dark:text-blue-400">
              <i class="fas fa-crown text-3xl"></i>
            </div>
            <div>
              <p class="text-sm font-medium text-blue-600 dark:text-blue-400 uppercase tracking-wider">Statut de membre</p>
              <h2 class="text-2xl font-bold text-theme">Membre Annuel LiveGood</h2>
            </div>
          </div>

          <div class="grid md:grid-cols-2 gap-8 py-6 border-y border-[var(--border)]">
            <div class="space-y-1">
              <p class="text-theme-muted text-xs font-medium uppercase tracking-widest">Date d'inscription</p>
              <p class="text-xl font-bold text-theme">16 Février 2025</p>
            </div>
            <div class="space-y-1">
              <p class="text-theme-muted text-xs font-medium uppercase tracking-widest">Valable jusqu'au</p>
              <p class="text-xl font-bold text-theme">16 Février 2026</p>
            </div>
          </div>

          <div class="mt-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-start gap-4 p-4 bg-green-50 dark:bg-green-900/20 rounded-2xl border border-green-100 dark:border-green-800 flex-1">
              <div class="w-10 h-10 bg-white dark:bg-gray-800 rounded-xl flex items-center justify-center shadow-sm shrink-0">
                <i class="fas fa-check text-green-600"></i>
              </div>
              <div>
                <p class="font-bold text-theme text-sm">Adhésion active</p>
                <p class="text-theme-muted text-xs mt-1">Vous économisez plus de 20% par rapport au forfait mensuel.</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Upgrade / Options Card (Dynamic based on current plan) -->
      <div v-if="isMonthly" class="bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20 rounded-3xl p-8 border border-blue-100 dark:border-blue-800">
        <div class="flex flex-col md:flex-row items-center gap-8">
          <div class="flex-1">
            <h3 class="text-xl font-bold text-theme mb-2">Passez à l'adhésion annuelle</h3>
            <p class="text-theme-muted">Économisez 20$ par an en payant 99,95$ en une seule fois au lieu de 9,95$ par mois.</p>
            
            <div class="mt-6 space-y-3">
              <div class="flex items-center gap-3 text-sm text-theme">
                <i class="fas fa-check-circle text-blue-500"></i>
                <span>Paiement unique de 99,95$</span>
              </div>
              <div class="flex items-center gap-3 text-sm text-theme">
                <i class="fas fa-check-circle text-blue-500"></i>
                <span>Tranquillité d'esprit pendant 12 mois</span>
              </div>
            </div>
          </div>
          <div class="w-full md:w-auto text-center">
            <p class="text-3xl font-extrabold text-blue-700 dark:text-blue-400 mb-4">99,95$</p>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-2xl font-bold shadow-lg shadow-blue-500/20 transition-all w-full">
              Mettre à jour maintenant
            </button>
          </div>
        </div>
      </div>

      <!-- Payment Info -->
      <div class="bg-[var(--card)] rounded-3xl p-6 shadow-sm border border-[var(--border)]">
        <h4 class="font-bold text-theme mb-4">Modes de paiement acceptés</h4>
        <div class="flex flex-wrap gap-4">
          <div class="flex items-center gap-3 px-4 py-2 bg-[var(--background)] rounded-xl border border-[var(--border)]">
            <i class="fab fa-cc-visa text-blue-600 text-xl"></i>
            <span class="text-sm font-medium text-theme">Visa / Mastercard</span>
          </div>
          <div class="flex items-center gap-3 px-4 py-2 bg-[var(--background)] rounded-xl border border-[var(--border)]">
            <i class="fab fa-bitcoin text-amber-500 text-xl"></i>
            <span class="text-sm font-medium text-theme">Crypto-monnaie</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

// Simuler si l'utilisateur est en forfait mensuel ou annuel
const isMonthly = ref(false) // Pour l'exemple on met False car Yohann est déjà Annuel dans le tuto
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
