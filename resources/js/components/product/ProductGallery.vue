<script setup>
import { computed, ref, watch, onBeforeUnmount } from 'vue'
import { onBeforeRouteLeave } from 'vue-router'
import VueEasyLightbox from 'vue-easy-lightbox'

const props = defineProps({
    product: { type: Object, required: true },
})

const images = computed(() => {
    const list = []
    if (props.product?.image) list.push(props.product.image)
    if (props.product?.gallery?.length) list.push(...props.product.gallery)
    return [...new Set(list)]
})

const selectedIndex = ref(0)
const lightboxVisible = ref(false)

const openLightbox = (index = 0) => {
    if (!images.value.length) return
    selectedIndex.value = index
    lightboxVisible.value = true
}

const closeLightbox = () => {
    lightboxVisible.value = false
}

watch(
    () => props.product?.id,
    () => {
        selectedIndex.value = 0
        closeLightbox()
    }
)

onBeforeRouteLeave(() => {
    closeLightbox()
})

onBeforeUnmount(() => {
    closeLightbox()
})
</script>

<template>
    <div>
        <button
            type="button"
            class="block w-full overflow-hidden rounded-3xl bg-white"
            @click="openLightbox()"
        >
            <img
                v-if="images.length"
                :src="images[selectedIndex]"
                :alt="product.title"
                class="aspect-[4/5] w-full object-cover"
            />

            <div
                v-else
                class="flex aspect-[4/5] items-center justify-center bg-gray-100 text-gray-400"
            >
               No Image
            </div>
        </button>

        <div
            v-if="images.length > 1"
            class="mt-4 flex gap-3 overflow-x-auto pb-2"
        >
            <button
                v-for="(image, index) in images"
                :key="image"
                type="button"
                class="h-20 w-20 shrink-0 overflow-hidden rounded-xl border"
                :class="selectedIndex === index ? 'border-black' : 'border-gray-200'"
                @click="selectedIndex = index"
            >
                <img
                    :src="image"
                    :alt="product.title"
                    class="h-full w-full object-cover"
                />
            </button>
        </div>

        <VueEasyLightbox
            v-if="lightboxVisible && images.length"
            :visible="lightboxVisible"
            :imgs="images"
            :index="selectedIndex"
            @hide="closeLightbox"
        />
    </div>
</template>