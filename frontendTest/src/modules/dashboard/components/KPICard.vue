<template>
  <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 hover:shadow-md transition">
    <div class="flex justify-between items-start">
      <div>
        <p class="text-gray-500 text-xs">{{ title }}</p>
        <p class="text-2xl font-bold text-gray-800">{{ value }}</p>
      </div>
      <div :class="['w-10 h-10 rounded-xl flex items-center justify-center', bgColor]">
        <i :class="[icon, iconColor, 'text-lg']"></i>
      </div>
    </div>
    <p :class="['text-xs mt-2', trendColor]">
      <i v-if="trendIcon" :class="trendIcon"></i> {{ trendText }}
    </p>
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
