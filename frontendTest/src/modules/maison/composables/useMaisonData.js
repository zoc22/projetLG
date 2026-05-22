// src/modules/maison/composables/useMaisonData.js
import { ref } from 'vue'

export function useMaisonData() {
  const contacts = ref([
    { initials: 'M3', name: 'MARTIN GORRY' },
    { initials: 'DG', name: 'David Alex GHAZAVI' },
    { initials: 'RS', name: 'Christophe Rossier' },
    { initials: 'PL', name: 'Pacôme Laile' },
    { initials: 'SA', name: 'Samson Agbonkro' }
  ])

  const products = ref([
    { name: 'SUPER GREENS', volume: '1500 mL', image: 'https://placehold.co/200x200/3b82f6/white?text=Super+Greens' },
    { name: 'SUPER REDS', volume: '1500 mL', image: 'https://placehold.co/200x200/ef4444/white?text=Super+Reds' }
  ])

  const websites = ref([
    {
      type: 'Site corporatif',
      url: 'https://LiveGood.com/yohann76',
      icon: 'fas fa-building',
      bgClass: 'bg-white border border-gray-200',
      darkBgClass: 'dark:bg-gray-800 dark:border-gray-700',
      badgeClass: 'bg-blue-100 text-blue-700',
      darkBadgeClass: 'dark:bg-blue-900/50 dark:text-blue-300'
    },
    {
      type: 'Site de vente au détail',
      url: 'https://ShopLiveGood.com/yohann76',
      icon: 'fas fa-tag',
      bgClass: 'bg-white border border-gray-200',
      darkBgClass: 'dark:bg-gray-800 dark:border-gray-700',
      badgeClass: 'bg-green-100 text-green-700',
      darkBadgeClass: 'dark:bg-green-900/50 dark:text-green-300'
    },
    {
      type: 'Page de destination Powerline',
      url: 'https://LiveGoodTour.com/yohann76',
      icon: 'fas fa-user-plus',
      bgClass: 'bg-gradient-to-br from-purple-50 to-indigo-50 border border-purple-200',
      darkBgClass: 'dark:bg-gradient-to-br dark:from-purple-900/30 dark:to-indigo-900/30 dark:border-purple-800',
      badgeClass: 'bg-purple-200 text-purple-800',
      darkBadgeClass: 'dark:bg-purple-900/50 dark:text-purple-300'
    }
  ])

  return { contacts, products, websites }
}