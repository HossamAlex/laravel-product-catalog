<script setup>
import ProductCard from '../product/ProductCard.vue'

defineProps({
    section: {
        type: Object,
        default: () => ({
            products: [],
        }),
    },
})
</script>

<template>
    <section
        v-if="section && section.products && section.products.length"
        class="mx-auto mt-12 max-w-7xl px-4"
    >
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-xl font-bold">{{ section.title }}</h2>

            <router-link
                :to="`/collection/${section.slug}`"
                class="text-sm text-gray-500"
            >
                show ALL
            </router-link>
        </div>

        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
            <ProductCard
                v-for="product in section.products"
                :key="product.id"
                :product="product"
            />
        </div>
    </section>
</template>