<template>
  <div :class="[site.cardClass, 'rounded-xl p-5 shadow-sm']">
    <div class="flex items-center justify-between mb-3">
      <i :class="[site.icon, 'text-2xl']"></i>
      <span :class="[site.badgeClass, 'px-2 py-0.5 rounded-full text-xs']">{{ site.type }}</span>
    </div>
    <p class="text-xs font-mono break-all">{{ site.url }}</p>
    <button @click="copyLink(site.url)" class="mt-4 w-full bg-gray-100 hover:bg-gray-200 py-2 rounded-lg text-sm transition">
      <i class="far fa-copy"></i> Copier
    </button>
  </div>
</template>

<script setup>
const props = defineProps({
  site: Object
})

const copyLink = (link) => {
  navigator.clipboard.writeText(link)
  alert('Lien copié !')
}
</script>
