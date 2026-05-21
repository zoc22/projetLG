<template>
  <div class="section-content">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">Boutique LiveGood</h1>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
      <ProductCard v-for="product in products" :key="product.name" :product="product" />
    </div>
  </div>
</template>

<script setup>
import ProductCard from './components/ProductCard.vue'
import { productsData } from '@/data/staticData'
import { ref } from 'vue'

const products = ref(productsData)
</script>
