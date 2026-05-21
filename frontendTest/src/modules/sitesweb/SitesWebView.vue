<template>
  <div class="section-content">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">Mes sites web</h1>
    <div class="grid md:grid-cols-3 gap-5">
      <SiteCard v-for="site in sitesWeb" :key="site.type" :site="site" />
    </div>
  </div>
</template>

<script setup>
import SiteCard from './components/SiteCard.vue'
import { sitesWebData } from '@/data/staticData'
import { ref } from 'vue'

const sitesWeb = ref(sitesWebData)
</script>
