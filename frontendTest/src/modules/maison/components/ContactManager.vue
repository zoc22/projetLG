<!-- src/modules/maison/components/ContactManager.vue -->
<template>
  <div class="mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 transition-colors">
      <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">
        Gestionnaire de contacts
      </h3>
      <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">
        Cliquez sur Gestionnaire de contacts pour voir et contacter vos clients et affiliés personnellement inscrits.
      </p>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
        <div v-for="contact in contacts" :key="contact.name"
             class="flex items-center justify-between p-3 border border-gray-100 dark:border-gray-700 rounded-xl">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-sm font-bold text-gray-600 dark:text-gray-300">
              {{ contact.initials }}
            </div>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ contact.name }}</span>
          </div>
          <button class="text-blue-600 dark:text-blue-400 text-sm hover:underline">Voir</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  contacts: Array
})
</script>