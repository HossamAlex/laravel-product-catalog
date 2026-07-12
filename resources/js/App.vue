<script setup>
import { onMounted, watch } from 'vue'
import Header from './components/layout/Header.vue'
import Footer from './components/layout/Footer.vue'
import { useSettingsStore } from './stores/settingsStore'
import { openWhatsApp } from './utils/whatsapp'

const settings = useSettingsStore()

const contactWhatsApp = () => {
    openWhatsApp(settings.whatsappMessage)
}

const updateDocumentMeta = () => {
    document.title = settings.siteName

    const favicon = settings.siteFavicon
    if (!favicon) return

    let link = document.querySelector('link[rel="icon"]')

    if (!link) {
        link = document.createElement('link')
        link.rel = 'icon'
        document.head.appendChild(link)
    }

    link.href = favicon
}

onMounted(async () => {
    try {
        await settings.load()
        updateDocumentMeta()
    } catch (error) {
        console.warn(error)
    }
})

watch(
    () => settings.values,
    updateDocumentMeta,
    { deep: true }
)
</script>

<template>
    <div dir="ltr" class="min-h-screen bg-neutral-50 text-gray-900">
        <Header />
        <router-view />
        <Footer />
        <button
            v-if="settings.whatsappNumber"
            type="button"
            class="fixed bottom-5 left-5 z-50 rounded-full bg-green-600 px-5 py-3 text-sm font-bold text-white shadow-lg md:hidden"
            @click="contactWhatsApp"
        >
            WhatsApp
        </button>
    </div>
</template>
