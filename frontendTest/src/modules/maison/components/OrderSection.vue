<!-- src/modules/maison/components/OrderSection.vue -->
<template>
  <div class="mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 transition-colors">
      <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
        <div>
          <h3 class="text-lg font-bold text-gray-800 dark:text-white">Commander un</h3>
          <p class="text-gray-500 dark:text-gray-400 text-sm">
            Prêt à commander plus de produits LiveGood qui changent la vie ?
          </p>
        </div>
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition shadow-md">
          ACHETEZ MAINTENANT
        </button>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <div v-for="product in products" :key="product.name"
             class="border border-gray-100 dark:border-gray-700 rounded-xl p-4 hover:shadow-md transition">
          <img :src="product.image" :alt="product.name" class="w-full h-40 object-contain mb-3">
          <h4 class="font-bold text-gray-800 dark:text-white text-center">{{ product.name }}</h4>
          <p class="text-xs text-gray-500 dark:text-gray-400 text-center">{{ product.volume }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  products: Array
})
</script>