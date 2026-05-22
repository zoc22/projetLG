import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { useNotificationStore } from './notificationStore'

export const useCartStore = defineStore('cart', () => {
  const cartItems = ref([])
  const favorites = ref([])
  const viewedProducts = ref([])
  const viewCounts = ref({}) // { productId: count }
  
  const notificationStore = useNotificationStore()

  const cartCount = computed(() => cartItems.value.reduce((acc, item) => acc + item.quantity, 0))
  const cartTotal = computed(() => {
    return cartItems.value.reduce((acc, item) => {
      const price = parseFloat(item.price.replace('$', ''))
      return acc + (price * item.quantity)
    }, 0).toFixed(2)
  })

  const addToCart = (product) => {
    const existing = cartItems.value.find(item => item.id === product.id)
    if (existing) {
      existing.quantity++
    } else {
      cartItems.value.push({ ...product, quantity: 1 })
    }
    notificationStore.add(`${product.name} ajouté au panier !`)
    addView(product)
  }

  const removeFromCart = (productId) => {
    const product = cartItems.value.find(item => item.id === productId)
    if (product) {
      cartItems.value = cartItems.value.filter(item => item.id !== productId)
      notificationStore.add(`${product.name} retiré du panier`, 'info')
    }
  }

  const toggleFavorite = (product) => {
    const index = favorites.value.findIndex(item => item.id === product.id)
    if (index > -1) {
      favorites.value.splice(index, 1)
      notificationStore.add(`${product.name} retiré des favoris`, 'info')
    } else {
      favorites.value.push(product)
      notificationStore.add(`${product.name} ajouté aux favoris !`)
    }
  }

  const isFavorite = (productId) => {
    return favorites.value.some(item => item.id === productId)
  }

  const addView = (product) => {
    if (!viewedProducts.value.some(p => p.id === product.id)) {
      viewedProducts.value.unshift(product)
      if (viewedProducts.value.length > 10) {
        viewedProducts.value.pop()
      }
      
      // Increment view count (simulated)
      if (!viewCounts.value[product.id]) {
        viewCounts.value[product.id] = Math.floor(Math.random() * 500) + 100 // Starting random count
      }
      viewCounts.value[product.id]++
    }
  }

  const getViewCount = (productId) => {
    return viewCounts.value[productId] || 0
  }

  return {
    cartItems,
    favorites,
    viewedProducts,
    viewCounts,
    cartCount,
    cartTotal,
    addToCart,
    removeFromCart,
    toggleFavorite,
    isFavorite,
    addView,
    getViewCount
  }
})
