<template>
  <div class="section-content max-w-6xl mx-auto">
    <!-- Marketplace Header -->
    <div class="mb-10">
      <h1 class="text-3xl font-extrabold text-theme">Bienvenue chez LiveGood</h1>
      <p class="text-theme-muted mt-2">Félicitations pour être devenu un membre certifié. Accédez aux prix les plus bas du marché.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Left Column: Main Actions -->
      <div class="lg:col-span-2 space-y-8">
        <!-- Quick Access Marketplace Style -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <router-link to="/boutique" class="flex flex-col p-8 bg-[var(--card)] rounded-3xl shadow-sm border border-[var(--border)] hover:shadow-xl hover:-translate-y-1 transition-all group">
            <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center text-blue-600 dark:text-blue-400 mb-6 group-hover:scale-110 transition-transform">
              <i class="fas fa-shopping-bag text-3xl"></i>
            </div>
            <h3 class="font-black text-theme text-xl uppercase tracking-tight">Accéder à la Boutique</h3>
            <p class="text-sm text-theme-muted mt-2">Plus de 20 produits exclusifs au prix membre.</p>
            <div class="mt-6 flex items-center gap-2 text-blue-600 dark:text-blue-400 font-bold text-sm">
              <span>Acheter maintenant</span>
              <i class="fas fa-arrow-right"></i>
            </div>
          </router-link>

          <router-link to="/equipe" class="flex flex-col p-8 bg-[var(--card)] rounded-3xl shadow-sm border border-[var(--border)] hover:shadow-xl hover:-translate-y-1 transition-all group">
            <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-2xl flex items-center justify-center text-green-600 dark:text-green-400 mb-6 group-hover:scale-110 transition-transform">
              <i class="fas fa-network-wired text-3xl"></i>
            </div>
            <h3 class="font-black text-theme text-xl uppercase tracking-tight">Ma Généalogie</h3>
            <p class="text-sm text-theme-muted mt-2">Suivez la croissance de votre matrice 2x15 en temps réel.</p>
            <div class="mt-6 flex items-center gap-2 text-green-600 dark:text-green-400 font-bold text-sm">
              <span>Voir mon équipe</span>
              <i class="fas fa-arrow-right"></i>
            </div>
          </router-link>
        </div>

        <!-- Affiliate Links Section -->
        <div class="bg-[var(--card)] rounded-3xl p-8 shadow-sm border border-[var(--border)]">
          <div class="flex items-center gap-4 mb-8">
            <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/20 rounded-xl flex items-center justify-center text-blue-600">
              <i class="fas fa-link text-xl"></i>
            </div>
            <div>
              <h3 class="text-xl font-black text-theme uppercase tracking-tight">Outils de Marketing</h3>
              <p class="text-sm text-theme-muted">Vos liens personnels pour promouvoir l'activité.</p>
            </div>
          </div>
          
          <div class="space-y-4">
            <div v-for="site in sitesWebData" :key="site.type" class="p-6 rounded-2xl border border-[var(--border)] bg-[var(--background)] hover:border-blue-200 dark:hover:border-blue-800 transition-all group">
              <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                  <div :class="['w-14 h-14 rounded-2xl flex items-center justify-center text-2xl shadow-sm', site.badgeClass]">
                    <i :class="site.icon"></i>
                  </div>
                  <div>
                    <h4 class="font-bold text-theme">Site {{ site.type }}</h4>
                    <p class="text-xs text-blue-600 dark:text-blue-400 font-mono mt-1 break-all">{{ site.url }}</p>
                  </div>
                </div>
                <div class="flex gap-3">
                  <button @click="copyLink(site.url)" class="flex-1 md:flex-none bg-[var(--card)] border border-[var(--border)] hover:bg-[var(--background)] text-theme opacity-80 px-6 py-2.5 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2">
                    <i class="fas fa-copy"></i> Copier
                  </button>
                  <a :href="site.url" target="_blank" class="flex-1 md:flex-none bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 shadow-lg shadow-blue-500/20">
                    <i class="fas fa-external-link-alt"></i> Visiter
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column -->
      <div class="space-y-8">
        <!-- Status Card -->
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl p-8 text-white shadow-xl shadow-blue-500/20 relative overflow-hidden">
          <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
          <div class="relative z-10">
            <div class="flex items-center gap-4 mb-8">
              <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-3xl font-black">YZ</div>
              <div>
                <p class="text-blue-100 text-xs font-bold uppercase tracking-widest">Yohann Zoc</p>
                <p class="text-2xl font-black">Affilié Bronze</p>
              </div>
            </div>
            <div class="space-y-4">
              <div class="flex justify-between items-center bg-white/10 p-3 rounded-2xl">
                <span class="text-blue-100 text-xs font-bold uppercase tracking-widest">Adhésion</span>
                <span class="bg-green-400 text-green-950 px-3 py-1 rounded-full text-[10px] font-black uppercase">Active</span>
              </div>
              <div class="flex justify-between items-center bg-white/10 p-3 rounded-2xl">
                <span class="text-blue-100 text-xs font-bold uppercase tracking-widest">Date Limite</span>
                <span class="font-black text-orange-300 uppercase text-xs">Jeu. 2-mars</span>
              </div>
            </div>
            <router-link to="/adhesion" class="mt-8 w-full bg-white text-blue-700 py-4 rounded-2xl font-black text-sm transition-all block text-center hover:bg-blue-50 shadow-lg active:scale-95">
              GÉRER MON COMPTE
            </router-link>
          </div>
        </div>

        <!-- Marketplace Ad -->
        <div class="bg-[var(--card)] rounded-3xl p-6 shadow-sm border border-[var(--border)] overflow-hidden relative group cursor-pointer">
          <div class="h-44 bg-gray-900 rounded-2xl overflow-hidden relative">
            <img src="https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?auto=format&fit=crop&w=600&q=80" class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:scale-110 transition-transform duration-700">
            <div class="absolute inset-0 flex flex-col justify-end p-6">
              <span class="text-white font-black text-lg leading-tight">Nouveaux CBD Broad Spectrum</span>
              <p class="text-blue-300 text-xs font-bold mt-1 uppercase tracking-widest">Découvrir l'offre</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { sitesWebData } from '@/data/staticData'

const copyLink = (url) => {
  navigator.clipboard.writeText(url)
  alert('Lien copié dans le presse-papier !')
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
