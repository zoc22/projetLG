<template>
  <div class="min-h-screen bg-[#0F172A] flex flex-col items-center justify-center p-4 text-center">
    <h1 class="text-9xl font-extrabold text-[#A3E635] tracking-widest">404</h1>
    <div class="bg-[#1E293B] px-2 text-sm rounded rotate-12 absolute text-white border border-gray-700">
      Page Non Trouvée
    </div>
    <div class="mt-8">
      <p class="text-gray-400 text-lg mb-8">
        Désolé, nous n'avons pas pu trouver la page que vous recherchez.
      </p>
      <router-link
        to="/dashboard"
        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-[#0F172A] bg-[#A3E635] hover:bg-[#bef264] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#A3E635] transition-colors"
      >
        Retour au Tableau de Bord
      </router-link>
    </div>
  </div>
</template>

<script setup>
// Composant 404 simple
</script>
