<script setup>
import { useSettingsStore } from '../../stores/settingsStore'

const settings = useSettingsStore()
</script>

<template>
    <footer class="mt-16 border-t bg-white py-8 text-center text-sm text-gray-500">
        {{ settings.siteName }} © {{ new Date().getFullYear() }}
    </footer>
</template>
