<template>
  <div class="section-content max-w-4xl mx-auto">
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Contactez-nous</h1>
      <p class="text-gray-500 dark:text-gray-400 mt-2">Notre équipe de support est très réactive et prête à vous aider.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <!-- Contact Info Cards -->
      <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex items-start gap-4">
          <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
            <i class="fas fa-envelope text-xl"></i>
          </div>
          <div>
            <h4 class="font-bold text-gray-900 dark:text-white">Email du Support</h4>
            <p class="text-blue-600 dark:text-blue-400 font-medium">support@livegood.com</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Réponse généralement sous 24h.</p>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex items-start gap-4">
          <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center text-green-600 dark:text-green-400 shrink-0">
            <i class="fas fa-map-marker-alt text-xl"></i>
          </div>
          <div>
            <h4 class="font-bold text-gray-900 dark:text-white">Adresse de retour produit</h4>
            <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">
              LiveGood Returns<br>
              1201 Jupiter Park Dr. Unit 5<br>
              Jupiter, FL 33458
            </p>
          </div>
        </div>

        <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-3xl p-6 border border-indigo-100 dark:border-indigo-800">
          <h4 class="font-bold text-indigo-900 dark:text-indigo-200 mb-2">Webinaires de support</h4>
          <p class="text-sm text-indigo-800 dark:text-indigo-400 leading-relaxed">
            Rejoignez nos sessions de formation en direct pour poser vos questions aux co-fondateurs.
          </p>
          <router-link to="/webinaires" class="mt-4 text-indigo-600 dark:text-indigo-400 font-bold text-sm flex items-center gap-2">
            Voir le calendrier <i class="fas fa-arrow-right"></i>
          </router-link>
        </div>
      </div>

      <!-- Contact Form -->
      <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Envoyez-nous un message</h3>
        <form @submit.prevent="sendMessage" class="space-y-4">
          <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Sujet</label>
            <select class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-100 dark:border-gray-600 rounded-xl px-4 py-3 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all">
              <option>Question sur l'adhésion</option>
              <option>Problème de commande</option>
              <option>Question sur les commissions</option>
              <option>Autre</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Message</label>
            <textarea rows="5" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-100 dark:border-gray-600 rounded-xl px-4 py-3 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all" placeholder="Comment pouvons-nous vous aider ?"></textarea>
          </div>
          <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-2xl font-bold shadow-lg shadow-blue-500/20 transition-all flex items-center justify-center gap-2">
            <i class="fas fa-paper-plane"></i> Envoyer le message
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
const sendMessage = () => {
  alert('Message envoyé avec succès ! Notre équipe reviendra vers vous rapidement.')
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
