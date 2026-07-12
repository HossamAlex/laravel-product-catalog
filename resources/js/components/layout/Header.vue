<script setup>
import { ref } from 'vue'
import SearchOverlay from './SearchOverlay.vue'
import MobileMenu from './MobileMenu.vue'
import { useSelectionStore } from '../../stores/selectionStore'
import SelectionDrawer from './SelectionDrawer.vue'
import { useSettingsStore } from '../../stores/settingsStore'

const settings = useSettingsStore()
const selectionOpen = ref(false)
const selection = useSelectionStore()
const mobileMenuOpen = ref(false)
const searchOpen = ref(false)
</script>

<template>
    <header class="sticky top-0 z-50 border-b bg-white">
        
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4">
            
            
            <router-link to="/" class="flex items-center gap-2">
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

            <nav class="hidden items-center gap-6 md:flex">
                
                <router-link to="/">Home</router-link>
                <router-link to="/products">Products</router-link>
                <router-link to="/categories">Categories</router-link>
            </nav>

            <div class="flex items-center gap-2">
    <button
        type="button"
        class="rounded-full border px-3 py-2 text-sm"
        @click="searchOpen = true"
    >
        🔍
    </button>

    <button
        type="button"
        class="relative rounded-full border px-3 py-2 text-sm"
        @click="selectionOpen = true"
    >
        🛍️
        <span
            v-if="selection.count"
            class="absolute -top-2 -right-2 rounded-full bg-black px-2 text-xs text-white"
        >
            {{ selection.count }}
        </span>
    </button>

   

    <button
        type="button"
        class="rounded-full border px-3 py-2 md:hidden"
        @click="mobileMenuOpen = true"
    >
        ☰
    </button>
</div>

           
        </div>
                <SelectionDrawer
                :open="selectionOpen"
                @close="selectionOpen = false"
            />
                    
        </header>

        <SearchOverlay
            :open="searchOpen"
            @close="searchOpen = false"
        />
        <MobileMenu
            :open="mobileMenuOpen"
            @close="mobileMenuOpen = false"
        />
</template>
