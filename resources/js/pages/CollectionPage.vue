<script setup>
import { onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import { CollectionService } from '../services/CollectionService'
import ProductCard from '../components/product/ProductCard.vue'

const route = useRoute()

const collection = ref(null)
const products = ref([])
const loading = ref(true)

onMounted(async () => {
    const response = await CollectionService.getCollection(route.params.slug)

    collection.value = response.data
    products.value = response.data.products || []

    loading.value = false
})
</script>

<template>
    <main class="mx-auto max-w-7xl px-4 py-8">
        <p v-if="loading">Loading...</p>

        <template v-else>
            <h1 class="mb-2 text-2xl font-bold">{{ collection.title }}</h1>
            <p v-if="collection.description" class="mb-6 text-gray-500">
                {{ collection.description }}
            </p>

            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                <ProductCard
                    v-for="product in products"
                    :key="product.id"
                    :product="product"
                />
            </div>
        </template>
    </main>
</template>