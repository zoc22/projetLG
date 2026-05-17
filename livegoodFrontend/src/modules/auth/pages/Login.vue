<template>
  <div class="flex min-h-screen items-center justify-center bg-[#F8FAFC] p-6">
    <div class="w-full max-w-md">
      <div class="mb-10 text-center">
        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-[#1E293B] text-white shadow-xl shadow-slate-200">
          <Globe2Icon :size="32" class="text-[#A3E635]" />
        </div>
        <h1 class="text-3xl font-black tracking-tight text-[#1E293B]">NETWORK<span class="text-[#A3E635]">LIVE</span></h1>
        <p class="mt-2 text-slate-500 font-medium italic">Accédez à votre succès mondial</p>
      </div>

      <Card class="border-t-4 border-t-[#A3E635]">
        <div class="mb-8">
          <h2 class="text-xl font-bold text-slate-900">Bienvenue</h2>
          <p class="text-sm text-slate-500">Connectez-vous à votre back-office</p>
        </div>

        <form @submit.prevent="handleSubmit" class="space-y-5">
          <div class="space-y-2">
            <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Email professionnel</label>
            <input 
              v-model="email"
              type="email" 
              placeholder="votre@email.com"
              class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition-all focus:border-[#A3E635] focus:bg-white focus:ring-4 focus:ring-green-100"
              required
            />
          </div>

          <div class="space-y-2">
            <div class="flex items-center justify-between">
              <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Mot de passe</label>
              <a href="#" class="text-xs font-semibold text-green-600 hover:underline">Oublié ?</a>
            </div>
            <input 
              v-model="password"
              type="password" 
              placeholder="••••••••"
              class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition-all focus:border-[#A3E635] focus:bg-white focus:ring-4 focus:ring-green-100"
              required
            />
          </div>

          <Button type="submit" class="w-full py-4 text-slate-900" :disabled="loading">
            <span v-if="loading">Authentification...</span>
            <span v-else class="flex items-center gap-2">
              Se connecter <ArrowRightIcon :size="18" />
            </span>
          </Button>

          <div class="relative flex items-center justify-center py-2">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-slate-200"></div>
            </div>
            <span class="relative bg-white px-4 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Ou continuer avec</span>
          </div>

          <Button 
            type="button" 
            variant="outline" 
            class="w-full border-slate-200 py-4"
            @click="handleGoogleLogin"
          >
            <ChromeIcon :size="18" class="mr-2 text-red-500" />
            Google Workspace
          </Button>
        </form>
      </Card>

      <p class="mt-8 text-center text-sm font-medium text-slate-500 font-sans">
        Pas encore membre ? 
        <a href="#" class="font-bold text-slate-900 hover:text-green-600 underline decoration-[#A3E635] decoration-2 underline-offset-4">
          Réservez votre position
        </a>
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { Globe2 as Globe2Icon, ArrowRight as ArrowRightIcon, Chrome as ChromeIcon } from 'lucide-vue-next';
import Card from '@/src/components/ui/Card.vue';
import Button from '@/src/components/ui/Button.vue';
import axios from 'axios';

import { useAuthStore } from '@/src/core/stores/authStore';

const email = ref('');
const password = ref('');
const loading = ref(false);
const router = useRouter();
const authStore = useAuthStore();

const handleGoogleLogin = async () => {
  try {
    const response = await axios.get('/api/auth/google/url');
    window.location.href = response.data.url;
  } catch (error) {
    console.error('Google login error', error);
  }
};

const handleSubmit = async () => {
  loading.value = true;
  try {
    await authStore.login(email.value, password.value);
    router.push('/dashboard');
  } catch (error: any) {
    console.error('Login failed', error);
    alert(error.response?.data?.message || 'Identifiants invalides');
  } finally {
    loading.value = false;
  }
};
</script>
