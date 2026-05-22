<template>
  <div class="bg-[var(--card)] p-5 rounded-3xl shadow-sm border border-[var(--border)] hover:shadow-md transition-all group">
    <div class="flex items-center gap-4">
      <div :class="['w-12 h-12 rounded-2xl flex items-center justify-center text-xl shrink-0 transition-transform group-hover:scale-110', bgColor]">
        <i :class="[icon, iconColor]"></i>
      </div>
      <div class="min-w-0">
        <p class="text-theme-muted text-xs font-bold uppercase tracking-widest truncate">{{ title }}</p>
        <p class="text-2xl font-black text-theme mt-0.5">{{ value }}</p>
      </div>
    </div>
    <div v-if="trendText" class="mt-4 pt-4 border-t border-[var(--border)] flex items-center gap-2">
      <i v-if="trendIcon" :class="[trendIcon, trendColor, 'text-xs']"></i>
      <span :class="['text-[10px] font-bold uppercase tracking-tight', trendColor]">{{ trendText }}</span>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: String,
  value: String,
  icon: String,
  bgColor: String,
  iconColor: String,
  trendText: String,
  trendColor: String,
  trendIcon: String
})
</script>
