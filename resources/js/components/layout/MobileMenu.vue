<script setup>
import { useSettingsStore } from '../../stores/settingsStore'

defineProps({
    open: {
        type: Boolean,
        default: false,
    },
})

const emit = defineEmits(['close'])
const settings = useSettingsStore()
</script>

<template>
    <div v-if="open" class="fixed inset-0 z-[998] bg-black/40" @click="emit('close')">
        <aside
            class="h-full w-80 max-w-[85%] bg-white p-6"
            @click.stop
        >
            <div class="mb-8 flex items-center justify-between">
                <router-link to="/" class="flex items-center gap-2" @click="emit('close')">
                    <img
                        v-if="settings.siteLogo"
                        :src="settings.siteLogo"
                        :alt="settings.siteName"
                        class="h-10 w-auto"
                    />

                    <span v-else class="text-xl font-bold">
                        {{ settings.siteName }}
                    </span>
                </router-link>

                <button type="button" @click="emit('close')">
                    ✕
                </button>
            </div>

            <nav class="flex flex-col gap-4 text-lg">
                <router-link to="/" @click="emit('close')">Home</router-link>
                <router-link to="/products" @click="emit('close')">Products</router-link>
                <router-link to="/categories" @click="emit('close')">Categories</router-link>
                <router-link to="/search" @click="emit('close')">Search</router-link>
            </nav>
        </aside>
    </div>
</template>
