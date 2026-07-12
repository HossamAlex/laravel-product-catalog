<script setup>
import { openWhatsApp, productWhatsAppMessage } from '../../utils/whatsapp'

defineProps({
    product: {
        type: Object,
        required: true,
    },
})

const askWhatsapp = (product, event) => {
    event.preventDefault()
    event.stopPropagation()

    openWhatsApp(productWhatsAppMessage(product))
}
</script>

<template>
    <router-link
        :to="`/product/${product.slug}`"
        class="group block overflow-hidden rounded-2xl bg-white shadow-sm"
    >
        <div class="aspect-[4/5] overflow-hidden bg-gray-100">
            <img
                v-if="product.image"
                :src="product.image"
                :alt="product.title"
                class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
            />
            
        </div>

        <div class="p-3">
            <h3 class="line-clamp-2 text-sm font-medium">
                {{ product.title }}
            </h3>

            <div class="mt-2 text-sm">
                <span v-if="product.special_price" class="font-bold">
                    {{ product.special_price }} KD
                </span>

                <span
                    v-if="product.special_price && product.price"
                    class="mr-2 text-gray-400 line-through"
                >
                    {{ product.price }} KD
                </span>

                <span v-else-if="product.price" class="font-bold">
                    {{ product.price }} KD
                </span>
                <button
    type="button"
    class="mt-3 w-full rounded-full border border-black py-2 text-xs font-semibold"
    @click="askWhatsapp(product, $event)"
>
    WhatsApp inquiry
</button>
            </div>
            
        </div>
    </router-link>
</template>