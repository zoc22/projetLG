<template>
  <div class="bg-[var(--card)] rounded-3xl p-6 shadow-sm border border-[var(--border)]">
    <h3 class="font-black text-theme mb-6 uppercase tracking-tight flex items-center gap-2">
      <i class="fas fa-clock text-blue-500"></i> Pré-inscriptions
    </h3>
    <div class="space-y-3">
      <div v-for="user in recentUsers" :key="user.name" class="flex justify-between items-center p-4 hover:bg-[var(--background)] rounded-2xl border border-transparent hover:border-[var(--border)] transition-all group">
        <div class="flex items-center gap-3">
          <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
            <i class="fas fa-user-plus text-xs"></i>
          </div>
          <span class="font-bold text-sm text-theme opacity-80">{{ user.name }}</span>
        </div>
        <span class="text-[10px] font-bold text-theme-muted uppercase">{{ user.time }}</span>
      </div>
    </div>
    <button class="w-full mt-6 py-3 bg-[var(--background)] hover:bg-blue-50 dark:hover:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-xl text-xs font-bold transition-colors uppercase tracking-widest border border-[var(--border)]">
      Voir tout le réseau
    </button>
  </div>
</template>

<script setup>
defineProps({
  recentUsers: Array
})
</script>
