<template>
  <div :class="[site.cardClass, 'rounded-3xl p-6 shadow-sm border dark:bg-gray-800 dark:border-gray-700 relative overflow-hidden group']">
    <div class="absolute -right-4 -top-4 opacity-5 group-hover:opacity-10 transition-opacity">
      <i :class="[site.icon, 'text-8xl']"></i>
    </div>
    
    <div class="flex items-center gap-4 mb-6">
      <div :class="['w-12 h-12 rounded-xl flex items-center justify-center text-xl', site.badgeClass]">
        <i :class="site.icon"></i>
      </div>
      <h3 class="font-bold text-gray-900 dark:text-white text-lg">Site {{ site.type }}</h3>
    </div>

    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 leading-relaxed">
      <span v-if="site.type === 'Corporatif'">Prix public et prix membre affichés. Parfait pour faire découvrir la gamme complète et l'opportunité.</span>
      <span v-else-if="site.type === 'Détail'">Uniquement les prix publics affichés. Utilisez ce lien pour générer des commissions sur les ventes directes.</span>
      <span v-else-if="site.type === 'Inscription'">Page de capture officielle LiveGood Tour. Idéal pour inscrire de nouveaux affiliés dans votre matrice.</span>
    </p>

    <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-xl border border-gray-100 dark:border-gray-600 mb-6">
      <p class="text-[10px] font-mono text-gray-400 dark:text-gray-500 break-all">{{ site.url }}</p>
    </div>

    <div class="grid grid-cols-2 gap-3">
      <button @click="copyLink(site.url)" class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 py-2.5 rounded-xl text-sm font-bold transition flex items-center justify-center gap-2">
        <i class="fas fa-copy"></i> Copier
      </button>
      <a :href="site.url" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-xl text-sm font-bold transition flex items-center justify-center gap-2">
        <i class="fas fa-external-link-alt"></i> Ouvrir
      </a>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  site: Object
})

const copyLink = (link) => {
  navigator.clipboard.writeText(link)
  alert('Lien copié dans le presse-papier !')
}
</script>
