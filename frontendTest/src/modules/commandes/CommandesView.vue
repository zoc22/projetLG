<template>
  <div class="section-content max-w-6xl mx-auto">
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-theme">Historique des commandes</h1>
      <p class="text-theme-muted mt-2">Suivez vos achats et téléchargez vos reçus.</p>
    </div>

    <div class="bg-[var(--card)] rounded-3xl shadow-sm border border-[var(--border)] overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left">
          <thead>
            <tr class="bg-[var(--background)]">
              <th class="px-6 py-4 text-xs font-bold text-theme-muted uppercase tracking-widest">Date</th>
              <th class="px-6 py-4 text-xs font-bold text-theme-muted uppercase tracking-widest">Désignation</th>
              <th class="px-6 py-4 text-xs font-bold text-theme-muted uppercase tracking-widest">Montant</th>
              <th class="px-6 py-4 text-xs font-bold text-theme-muted uppercase tracking-widest">Statut</th>
              <th class="px-6 py-4 text-xs font-bold text-theme-muted uppercase tracking-widest text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[var(--border)]">
            <tr v-for="order in orders" :key="order.date" class="hover:bg-[var(--background)] transition-colors">
              <td class="px-6 py-4">
                <span class="text-sm font-semibold text-theme opacity-80">{{ order.date }}</span>
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center text-blue-600 dark:text-blue-400 text-xs">
                    <i class="fas fa-box"></i>
                  </div>
                  <span class="text-sm font-bold text-theme">{{ order.product }}</span>
                </div>
              </td>
              <td class="px-6 py-4">
                <span class="text-sm font-extrabold text-theme">{{ order.amount }}</span>
              </td>
              <td class="px-6 py-4">
                <span :class="['px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider', order.status === 'Livrée' ? 'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400' : 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400']">
                  {{ order.status }}
                </span>
              </td>
              <td class="px-6 py-4 text-right">
                <button @click="printReceipt(order)" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-bold text-sm flex items-center gap-2 ml-auto">
                  <i class="fas fa-print"></i> Reçu
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <div v-if="orders.length === 0" class="p-12 text-center">
        <div class="w-20 h-20 bg-[var(--background)] rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
          <i class="fas fa-shopping-bag text-3xl"></i>
        </div>
        <p class="text-theme-muted font-medium">Vous n'avez pas encore passé de commande.</p>
        <router-link to="/boutique" class="mt-4 text-blue-600 font-bold hover:underline inline-block">Visiter la boutique</router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ordersData } from '@/data/staticData'
import { ref } from 'vue'

const orders = ref(ordersData)

const printReceipt = (order) => {
  alert(`Préparation de l'impression pour la commande : ${order.product} (${order.amount})`)
}
</script>

<style scoped>
.section-content {
  animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes slideUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
