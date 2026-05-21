<template>
  <div class="space-y-8 animate-in fade-in duration-700">
    <div class="border-b border-slate-200 pb-8">
      <h1 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-3 uppercase">
        <UserIcon :size="32" class="text-blue-500" /> Mes Informations
      </h1>
      <p class="mt-2 text-slate-500 font-medium">Gérez vos paramètres personnels et de profil</p>
    </div>

    <div v-if="loading" class="py-20 text-center">
       <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div>
    </div>

    <Card v-else-if="user" class="max-w-xl mx-auto py-12 text-center border-t-4 border-blue-500 shadow-2xl">
       <div class="relative w-max mx-auto mb-6">
         <div class="h-24 w-24 rounded-full bg-slate-100 flex items-center justify-center text-blue-500 font-black text-4xl border-2 border-blue-100 shadow-inner overflow-hidden">
           <img v-if="user.avatar_url" :src="user.avatar_url" class="h-full w-full object-cover" />
           <span v-else>{{ user.name.charAt(0) }}</span>
         </div>
         <button 
           @click="triggerFileInput"
           class="absolute bottom-0 right-0 p-2 bg-blue-500 text-white rounded-full border-4 border-white shadow-lg hover:bg-blue-600 transition-colors"
           :disabled="uploading"
         >
           <LoaderIcon v-if="uploading" class="h-4 w-4 animate-spin" />
           <CameraIcon v-else class="h-4 w-4" />
         </button>
         <input 
           ref="fileInput"
           type="file" 
           class="hidden" 
           accept="image/*"
           @change="handleFileUpload"
         />
       </div>
       <h3 class="text-2xl font-black text-slate-900 mb-1 uppercase tracking-tight">{{ user.name }}</h3>
       <p class="text-slate-400 font-medium mb-2">{{ user.email }}</p>
       <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-300 mb-10">ID: {{ user.id }}</p>
       
       <div class="space-y-3">
         <Button variant="outline" class="w-full font-bold uppercase tracking-widest text-[10px] py-6 rounded-xl">Modifier mon profil</Button>
         <Button variant="outline" class="w-full font-bold uppercase tracking-widest text-[10px] py-6 rounded-xl">Changer de mot de passe</Button>
       </div>
    </Card>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { User as UserIcon, Camera as CameraIcon, Check as CheckIcon, Loader2 as LoaderIcon } from 'lucide-vue-next';
import Card from '@/components/ui/Card.vue';
import Button from '@/components/ui/Button.vue';

const user = ref<any>(null);
const loading = ref(true);
const uploading = ref(false);
const fileInput = ref<HTMLInputElement | null>(null);

const fetchUser = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/profile/me');
        user.value = response.data;
    } catch (error) {
        console.error("Error fetching profile", error);
    } finally {
        loading.value = false;
    }
};

onMounted(fetchUser);

const triggerFileInput = () => {
    fileInput.value?.click();
};

const handleFileUpload = async (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];
    if (!file) return;

    uploading.value = true;
    try {
        // Convert to base64 for simulation
        const reader = new FileReader();
        reader.onloadend = async () => {
            const base64String = reader.result as string;
            await axios.post('/api/profile/update-avatar', { avatar_url: base64String });
            await fetchUser();
        };
        reader.readAsDataURL(file);
    } catch (e) {
        console.error("Upload failed", e);
    } finally {
        uploading.value = false;
    }
};
</script>

