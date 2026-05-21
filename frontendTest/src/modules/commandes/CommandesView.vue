<template>
  <div class="section-content">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">Historique des commandes</h1>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 overflow-x-auto">
      <table class="w-full text-left">
        <thead class="bg-gray-50">
          <tr>
            <th class="p-3 text-gray-700">Date</th>
            <th class="text-gray-700">Produit</th>
            <th class="text-gray-700">Montant</th>
            <th class="text-gray-700">Statut</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="order in orders" :key="order.date" class="border-b">
            <td class="p-3">{{ order.date }}</td>
            <td>{{ order.product }}</td>
            <td>{{ order.amount }}</td>
            <td :class="order.statusClass">{{ order.status }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ordersData } from '@/data/staticData'
import { ref } from 'vue'

const orders = ref(ordersData)
</script>
