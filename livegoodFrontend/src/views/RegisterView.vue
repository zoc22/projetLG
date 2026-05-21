<template>
  <div class="min-h-screen bg-[#0F172A] flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <h2 class="mt-6 text-center text-3xl font-extrabold text-white">Créer votre compte LiveGood</h2>
      <p class="mt-2 text-center text-sm text-gray-400">
        Déjà inscrit ?
        <router-link to="/auth/login" class="font-medium text-[#A3E635] hover:text-[#bef264]">Se connecter</router-link>
      </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-[#1E293B] py-8 px-4 shadow sm:rounded-lg sm:px-10 border border-gray-700">
        <form class="space-y-6" @submit.prevent="handleRegister">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label for="prenom" class="block text-sm font-medium text-gray-300">Prénom</label>
              <div class="mt-1">
                <input v-model="form.prenom" id="prenom" name="prenom" type="text" required class="appearance-none block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#A3E635] focus:border-[#A3E635] bg-[#0F172A] text-white sm:text-sm" />
              </div>
            </div>
            <div>
              <label for="nom" class="block text-sm font-medium text-gray-300">Nom</label>
              <div class="mt-1">
                <input v-model="form.nom" id="nom" name="nom" type="text" required class="appearance-none block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#A3E635] focus:border-[#A3E635] bg-[#0F172A] text-white sm:text-sm" />
              </div>
            </div>
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
            <div class="mt-1">
              <input v-model="form.email" id="email" name="email" type="email" required class="appearance-none block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#A3E635] focus:border-[#A3E635] bg-[#0F172A] text-white sm:text-sm" />
            </div>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-300">Mot de passe</label>
            <div class="mt-1">
              <input v-model="form.password" id="password" name="password" type="password" required class="appearance-none block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#A3E635] focus:border-[#A3E635] bg-[#0F172A] text-white sm:text-sm" />
            </div>
          </div>

          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-300">Confirmer le mot de passe</label>
            <div class="mt-1">
              <input v-model="form.password_confirmation" id="password_confirmation" name="password_confirmation" type="password" required class="appearance-none block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#A3E635] focus:border-[#A3E635] bg-[#0F172A] text-white sm:text-sm" />
            </div>
          </div>

          <div>
            <label for="parrain_code" class="block text-sm font-medium text-gray-300">Code Parrain (ID)</label>
            <div class="mt-1">
              <input v-model="form.parrain_code" id="parrain_code" name="parrain_code" type="text" placeholder="Ex: UUID du parrain" class="appearance-none block w-full px-3 py-2 border border-gray-600 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#A3E635] focus:border-[#A3E635] bg-[#0F172A] text-white sm:text-sm" />
            </div>
          </div>

          <div class="flex items-center">
            <input v-model="form.accept_terms" id="accept_terms" name="accept_terms" type="checkbox" required class="h-4 w-4 text-[#A3E635] focus:ring-[#A3E635] border-gray-600 rounded bg-[#0F172A]" />
            <label for="accept_terms" class="ml-2 block text-sm text-gray-300"> J'accepte les conditions générales </label>
          </div>

          <div>
            <button type="submit" :disabled="isLoading" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-[#0F172A] bg-[#A3E635] hover:bg-[#bef264] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#A3E635] disabled:opacity-50">
              <span v-if="isLoading">Inscription...</span>
              <span v-else>S'inscrire</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../core/stores/authStore'
import { useNotificationStore } from '../core/stores/notificationStore'

const router = useRouter()
const authStore = useAuthStore()
const notificationStore = useNotificationStore()

const isLoading = ref(false)
const form = reactive({
  nom: '',
  prenom: '',
  email: '',
  password: '',
  password_confirmation: '',
  parrain_code: '',
  role: 'affiliate',
  accept_terms: false
})

const handleRegister = async () => {
  isLoading.value = true
  try {
    await authStore.register({
      nom: form.nom,
      prenom: form.prenom,
      email: form.email,
      password: form.password,
      password_confirmation: form.password_confirmation,
      parrain_code: form.parrain_code || null,
      role: form.role,
      accept_terms: form.accept_terms
    })
    notificationStore.success('Inscription réussie !')
    router.push('/dashboard')
  } catch (error: any) {
    console.error('Registration failed:', error)
    notificationStore.error(error.response?.data?.message || 'Erreur lors de l\'inscription')
  } finally {
    isLoading.value = false
  }
}
</script>
