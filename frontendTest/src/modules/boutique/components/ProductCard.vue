<template>
  <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-md transition">
    <div class="p-4">
      <img :src="product.image" class="w-full h-36 object-cover rounded-lg mb-3">
      <h3 class="font-bold text-gray-800">{{ product.name }}</h3>
      <p class="text-sm text-gray-500">{{ product.desc }}</p>
      <div class="flex justify-between items-center mt-3">
        <span class="text-xl font-bold text-blue-600">{{ product.price }}</span>
        <span class="text-sm text-gray-400 line-through">{{ product.oldPrice }}</span>
        <button class="bg-blue-500 text-white px-3 py-1 rounded-lg text-sm">Commander</button>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  product: Object
})
</script>
