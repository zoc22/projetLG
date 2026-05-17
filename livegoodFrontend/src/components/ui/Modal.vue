<template>
  <Teleport to="body">
    <Transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" @click.self="$emit('close')">
        <Transition
          enter-active-class="transition duration-300 ease-out"
          enter-from-class="opacity-0 scale-95 translate-y-4"
          enter-to-class="opacity-100 scale-100 translate-y-0"
          leave-active-class="transition duration-200 ease-in"
          leave-from-class="opacity-100 scale-100 translate-y-0"
          leave-to-class="opacity-0 scale-95 translate-y-4"
        >
          <div v-if="show" class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
              <h3 class="text-lg font-black text-slate-900 uppercase tracking-tight">{{ title }}</h3>
              <button @click="$emit('close')" class="text-slate-400 hover:text-slate-600 transition-colors">
                <XIcon class="h-6 w-6" />
              </button>
            </div>
            <div class="p-8">
              <slot></slot>
            </div>
          </div>
        </Transition>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup lang="ts">
import { X as XIcon } from 'lucide-vue-next';

defineProps<{
  show: boolean;
  title: string;
}>();

defineEmits(['close']);
</script>
