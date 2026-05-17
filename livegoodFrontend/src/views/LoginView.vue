<template>
  <div class="min-h-screen bg-gradient-to-br from-[#1E293B] to-[#0F172A] flex items-center justify-center p-4">
    <div class="w-full max-w-md">
      <!-- Logo -->
      <div class="text-center mb-8">
        <div class="flex items-center justify-center gap-2 mb-4">
          <div class="bg-white p-2 rounded-lg text-[#1E293B]">
            <Globe2Icon :size="32" />
          </div>
          <span class="text-3xl font-bold text-white">LIVE<span class="text-[#A3E635]">GOOD</span></span>
        </div>
        <p class="text-slate-400">Connexion à votre compte</p>
      </div>

      <!-- Login Form -->
      <div class="bg-slate-800 rounded-2xl p-8 shadow-2xl border border-slate-700/50">
        <form @submit.prevent="handleLogin" class="space-y-4">
          <!-- Email -->
          <div>
            <label class="block text-sm font-medium text-slate-300 mb-2">Email</label>
            <input
              v-model="email"
              type="email"
              required
              class="w-full px-4 py-2 rounded-lg bg-slate-700 border border-slate-600 text-white placeholder-slate-400 focus:outline-none focus:border-[#A3E635] focus:ring-2 focus:ring-[#A3E635]/20"
              placeholder="votre@email.com"
            />
          </div>

          <!-- Password -->
          <div>
            <label class="block text-sm font-medium text-slate-300 mb-2">Mot de passe</label>
            <input
              v-model="password"
              type="password"
              required
              class="w-full px-4 py-2 rounded-lg bg-slate-700 border border-slate-600 text-white placeholder-slate-400 focus:outline-none focus:border-[#A3E635] focus:ring-2 focus:ring-[#A3E635]/20"
              placeholder="••••••••"
            />
          </div>

          <!-- Remember Me -->
          <div class="flex items-center">
            <input v-model="rememberMe" type="checkbox" id="remember" class="rounded" />
            <label for="remember" class="ml-2 text-sm text-slate-400">Se souvenir de moi</label>
          </div>

          <!-- Error Message -->
          <div v-if="error" class="bg-red-500/10 border border-red-500/50 text-red-400 px-4 py-2 rounded-lg text-sm">
            {{ error }}
          </div>

          <!-- Submit Button -->
          <button
            :disabled="isLoading"
            type="submit"
            class="w-full bg-[#A3E635] hover:bg-[#84CC16] text-slate-900 font-bold py-2 px-4 rounded-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed mt-6"
          >
            {{ isLoading ? "Connexion..." : "Se connecter" }}
          </button>
        </form>

        <!-- Register Link -->
        <div class="text-center mt-6 text-slate-400">
          Pas encore de compte?
          <router-link to="/auth/register" class="text-[#A3E635] hover:underline">S'inscrire</router-link>
        </div>

        <!-- Forgot Password Link -->
        <div class="text-center mt-2">
          <router-link to="/auth/forgot-password" class="text-slate-400 hover:text-[#A3E635] text-sm">
            Mot de passe oublié?
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { useRouter } from "vue-router";
import { Globe2Icon } from "lucide-vue-next";
import { useAuthStore } from "../../core/stores/authStore";

const router = useRouter();
const authStore = useAuthStore();

const email = ref("");
const password = ref("");
const rememberMe = ref(true);
const isLoading = ref(false);
const error = ref("");

const handleLogin = async () => {
  try {
    isLoading.value = true;
    error.value = "";
    
    await authStore.login(email.value, password.value);
    
    router.push("/dashboard");
  } catch (err: any) {
    error.value = err.response?.data?.message || "Erreur de connexion";
  } finally {
    isLoading.value = false;
  }
};
</script>
