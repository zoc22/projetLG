<template>
  <div class="section-content max-w-4xl mx-auto">
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Mon recruteur</h1>
      <p class="text-gray-500 dark:text-gray-400 mt-2">N'hésitez pas à contacter votre parrain pour obtenir de l'aide et des conseils.</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden">
      <div class="absolute top-0 right-0 p-8 opacity-5 dark:opacity-10">
        <i class="fas fa-user-tie text-9xl"></i>
      </div>

      <div class="relative z-10">
        <div class="flex flex-col md:flex-row items-center gap-8">
          <div class="w-32 h-32 rounded-3xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white text-4xl font-bold shadow-xl shadow-blue-500/20">
            JD
          </div>
          <div class="text-center md:text-left">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Jean Dupont</h2>
            <div class="flex items-center justify-center md:justify-start gap-2 mt-2">
              <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs font-bold rounded-full">Affilié Argent</span>
              <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-bold rounded-full">En ligne</span>
            </div>
            <p class="text-gray-500 dark:text-gray-400 mt-4 leading-relaxed max-w-md">
              "Bienvenue dans mon équipe ! Je suis là pour vous accompagner dans votre réussite chez LiveGood. Contactez-moi pour toute question sur le plan de rémunération."
            </p>
          </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4 mt-10">
          <a href="mailto:jean.dupont@livegood.com" class="flex items-center gap-4 p-5 bg-gray-50 dark:bg-gray-700/50 rounded-2xl border border-gray-100 dark:border-gray-600 hover:border-blue-200 dark:hover:border-blue-800 transition-all group">
            <div class="w-10 h-10 bg-white dark:bg-gray-800 rounded-xl flex items-center justify-center text-blue-600 shadow-sm group-hover:scale-110 transition-transform">
              <i class="fas fa-envelope"></i>
            </div>
            <div>
              <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Email</p>
              <p class="text-sm font-bold text-gray-800 dark:text-white">jean.dupont@livegood.com</p>
            </div>
          </a>
          <a href="tel:+33698765432" class="flex items-center gap-4 p-5 bg-gray-50 dark:bg-gray-700/50 rounded-2xl border border-gray-100 dark:border-gray-600 hover:border-green-200 dark:hover:border-green-800 transition-all group">
            <div class="w-10 h-10 bg-white dark:bg-gray-800 rounded-xl flex items-center justify-center text-green-600 shadow-sm group-hover:scale-110 transition-transform">
              <i class="fas fa-phone"></i>
            </div>
            <div>
              <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Téléphone</p>
              <p class="text-sm font-bold text-gray-800 dark:text-white">+33 6 98 76 54 32</p>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// Données statiques pour la démo
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
