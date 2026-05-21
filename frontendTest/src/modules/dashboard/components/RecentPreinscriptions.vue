<template>
  <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
    <h3 class="font-bold text-gray-800 mb-4">
      <i class="fas fa-clock text-blue-500"></i> Préinscriptions récentes
    </h3>
    <div class="space-y-2">
      <div v-for="user in recentUsers" :key="user.name" class="flex justify-between items-center p-2 hover:bg-gray-50 rounded">
        <span><i class="fas fa-user text-gray-400"></i> {{ user.name }}</span>
        <span class="text-xs text-gray-500">{{ user.time }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  recentUsers: Array
})
</script>
