<!-- src/modules/maison/components/WebsitesSection.vue -->
<template>
  <div class="mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 transition-colors">
      <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Mes sites Web</h3>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div v-for="site in websites" :key="site.type"
             :class="[
               'rounded-xl p-5 shadow-sm transition-colors',
               site.bgClass,
               site.darkBgClass || 'dark:bg-gray-700 dark:border-gray-600'
             ]">
          <div class="flex items-center justify-between mb-3">
            <i :class="[site.icon, 'text-2xl text-blue-500 dark:text-blue-400']"></i>
            <span :class="[site.badgeClass, 'px-2 py-0.5 rounded-full text-xs', site.darkBadgeClass]">{{ site.type }}</span>
          </div>
          <p class="text-xs font-mono break-all text-gray-600 dark:text-gray-300">{{ site.url }}</p>
          <button @click="copyLink(site.url)"
                  class="mt-4 w-full bg-gray-100 dark:bg-gray-600 hover:bg-gray-200 dark:hover:bg-gray-500 py-2 rounded-lg text-sm transition">
            <i class="far fa-copy mr-1"></i> Copier
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  websites: Array
})

const copyLink = (url) => {
  navigator.clipboard.writeText(url)
  alert('Lien copié !')
}
</script>