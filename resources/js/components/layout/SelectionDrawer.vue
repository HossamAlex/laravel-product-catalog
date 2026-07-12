<script setup>
import { watch } from 'vue'
import { useSelectionStore } from '../../stores/selectionStore'
import { openWhatsApp } from '../../utils/whatsapp'
import { useSettingsStore } from '../../stores/settingsStore'

const props = defineProps({
    open: {
        type: Boolean,
        default: false,
    },
})

const emit = defineEmits(['close'])

const selection = useSelectionStore()
const settings = useSettingsStore()

watch(
    () => props.open,
    (isOpen) => {
        if (isOpen) selection.hydrateMissingImages()
    },
    { immediate: true }
)

const sendToWhatsApp = () => {
    if (!selection.items.length) return

    const message = [
        settings.whatsappMessage,
        '',
        ...selection.items.map((item) => {
            return `- ${item.sku} | ${item.title} | ${window.location.origin}/product/${item.slug}`
        }),
    ].join('\n')

    openWhatsApp(message)
}
</script>

<template>
    <div
        v-if="open"
        class="fixed inset-0 z-[999] bg-black/40"
        @click="emit('close')"
    >
        <aside
            class="mr-auto h-full w-96 max-w-[90%] overflow-y-auto bg-white p-5"
            @click.stop
        >
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-xl font-bold">My choices</h2>

                <button type="button" @click="emit('close')">
                    ✕
                </button>
            </div>

            <p v-if="!selection.items.length" class="text-center text-gray-400">
                لا توجد منتجات في اختياراتك
            </p>

            <div v-else class="space-y-4">
                <div
                    v-for="item in selection.items"
                    :key="item.id"
                    class="flex gap-3 rounded-2xl border p-3"
                >
                    <router-link
                        :to="`/product/${item.slug}`"
                        class="h-20 w-20 shrink-0 overflow-hidden rounded-xl bg-gray-100"
                        @click="emit('close')"
                    >
                        <img
                            v-if="item.image"
                            :src="item.image"
                            :alt="item.title"
                            class="h-full w-full object-cover"
                        />

                        <span
                            v-else
                            class="flex h-full w-full items-center justify-center px-2 text-center text-xs text-gray-400"
                        >
                           No Image
                        </span>
                    </router-link>

                    <div class="flex-1">
                        <p class="text-sm text-gray-400">{{ item.sku }}</p>
                        <h3 class="font-semibold">{{ item.title }}</h3>
                        <p v-if="item.price" class="mt-1 text-sm font-bold">
                            {{ item.price }} KD
                        </p>

                        <button
                            type="button"
                            class="mt-2 text-sm text-red-500"
                            @click="selection.remove(item.id)"
                        >
                            Delete
                        </button>
                    </div>
                </div>

                <button
                    type="button"
                    class="w-full rounded-full bg-black py-4 text-white"
                    @click="sendToWhatsApp"
                >
                   send all in whatsapp
                </button>

                <button
                    type="button"
                    class="w-full rounded-full border py-3"
                    @click="selection.clear()"
                >
                    Delete ALL
                </button>
            </div>
        </aside>
    </div>
</template>
