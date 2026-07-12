<script setup>
import { onMounted, ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import { ProductService } from '../services/ProductService'
import ProductCard from '../components/product/ProductCard.vue'

const route = useRoute()

const products = ref([])
const loading = ref(true)
const keyword = ref(route.query.q || '')

const loadResults = async () => {
    loading.value = true

    const response = await ProductService.getProducts({
        search: keyword.value,
        per_page: 48,
    })

    products.value = response.data?.data || response.data || []

    loading.value = false
}

onMounted(loadResults)

watch(
    () => route.query.q,
    () => {
        keyword.value = route.query.q || ''
        loadResults()
    }
)
</script>

<template>
    <main class="mx-auto max-w-7xl px-4 py-8">
        <h1 class="mb-6 text-2xl font-bold">
            Search Result: {{ keyword }}
        </h1>

        <p v-if="loading">Loading...</p>

        <div
            v-else-if="products.length"
            class="grid grid-cols-2 gap-4 md:grid-cols-4"
        >
            <ProductCard
                v-for="product in products"
                :key="product.id"
                :product="product"
            />
        </div>

        <p v-else class="text-center text-gray-400">
            لا توجد نتائج لهذا البحث
        </p>
    </main>
</template>