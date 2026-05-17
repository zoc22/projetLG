<template>
  <button
    :class="[
      'inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition-all focus:outline-none disabled:opacity-50 disabled:pointer-events-none',
      variantClasses[variant],
      sizeClasses[size],
      $attrs.class
    ]"
    v-bind="$attrs"
  >
    <slot />
  </button>
</template>

<script setup lang="ts">
interface Props {
  variant?: 'default' | 'outline' | 'ghost' | 'danger';
  size?: 'sm' | 'md' | 'lg';
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'default',
  size: 'md'
});

const variantClasses = {
  default: 'bg-[#A3E635] text-[#1E293B] hover:bg-[#82BA2A] shadow-md shadow-green-200',
  outline: 'border border-slate-200 bg-white text-slate-700 hover:bg-slate-50',
  ghost: 'text-slate-600 hover:bg-slate-100 hover:text-slate-900',
  danger: 'bg-red-600 text-white hover:bg-red-700'
};

const sizeClasses = {
  sm: 'px-3 py-1.5 text-xs',
  md: 'px-4 py-2',
  lg: 'px-6 py-3 text-base'
};
</script>
