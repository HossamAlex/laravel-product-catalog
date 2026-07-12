<script setup>
import { onMounted, ref } from 'vue'
import { CategoryService } from '../services/CategoryService'
import { ProductService } from '../services/ProductService'
import ProductFilters from '../components/filters/ProductFilters.vue'
import ProductCard from '../components/product/ProductCard.vue'


const categories = ref([])
const products = ref([])
const currentFilters = ref({})
const pagination = ref(null)
const page = ref(1)

const initialLoading = ref(true)
const productsLoading = ref(false)
const error = ref(null)


const productList = (response) => response.data || []


const extractPagination = (response) => response.meta || null

const loadProducts = async () => {
    productsLoading.value = true
    error.value = null

    try {
        const response = await ProductService.getProducts({
            ...currentFilters.value,
            per_page: 24,
            page: page.value,
        })

        products.value = response.data || []
        pagination.value = response.meta || null
    } catch (e) {
        error.value = 'An error occurred while loading the products'
    } finally {
        productsLoading.value = false
    }
}

const onFilterChange = (filters) => {
    currentFilters.value = filters
    page.value = 1
    loadProducts()
}

const goToPage = (newPage) => {
    if (!pagination.value) return
    if (newPage < 1 || newPage > pagination.value.last_page) return

    page.value = newPage
    loadProducts()

    window.scrollTo({
        top: 0,
        behavior: 'smooth',
    })
}

onMounted(async () => {
    try {
        const categoriesResponse = await CategoryService.getCategories()
        categories.value = categoriesResponse.data || categoriesResponse

        await loadProducts()
    } catch (e) {
        error.value = 'An error occurred while loading products'
    } finally {
        initialLoading.value = false
    }
})
</script>

<template>
    <main class="mx-auto max-w-7xl px-4 py-8">
        <p v-if="initialLoading" class="text-center">Loading...</p>

        <template v-else>
            <section v-if="categories.length" class="mb-8">
                <h1 class="mb-4 text-2xl font-bold">Main Categories</h1>

                <div class="scrollbar-hidden flex snap-x gap-3 overflow-x-auto pb-2 md:grid md:grid-cols-4 md:overflow-visible md:pb-0">
                    <router-link
                        v-for="category in categories"
                        :key="category.id"
                        :to="`/category/${category.slug}`"
                        class="min-w-max snap-start rounded-full bg-white px-5 py-2.5 text-sm font-medium shadow-sm md:rounded-2xl md:p-5 md:text-center md:text-base md:font-bold"
                    >
                        {{ category.title }}
                    </router-link>
                </div>
            </section>

            <ProductFilters
                :selected-brand="currentFilters.brand || ''"
                @change="onFilterChange"
            />

            <div class="mb-5 flex items-center justify-between">
                <h2 class="text-xl font-bold">All Products</h2>

                <span v-if="pagination" class="text-sm text-gray-500">
                    {{ pagination.total }} Product(s)
                </span>
            </div>

            <p v-if="productsLoading" class="mb-4 text-center text-gray-400">
                Products are being updated...
            </p>

            <p v-if="error" class="text-center text-red-500">
                {{ error }}
            </p>

            <div v-else-if="products.length" class="grid grid-cols-2 gap-4 md:grid-cols-4">
                <ProductCard
                    v-for="product in products"
                    :key="product.id"
                    :product="product"
                />
            </div>

            <p v-else-if="!productsLoading" class="text-center text-gray-400">
                No products found
            </p>

            <div
                v-if="pagination && pagination.last_page > 1"
                class="mt-10 flex items-center justify-center gap-2"
            >
                <button
                    type="button"
                    class="rounded-full border px-4 py-2 disabled:opacity-40"
                    :disabled="pagination.current_page === 1 || productsLoading"
                    @click="goToPage(pagination.current_page - 1)"
                >
                    Previous
                </button>

                <span class="px-4 text-sm text-gray-500">
                    Page {{ pagination.current_page }} of {{ pagination.last_page }}
                </span>

                <button
                    type="button"
                    class="rounded-full border px-4 py-2 disabled:opacity-40"
                    :disabled="pagination.current_page === pagination.last_page || productsLoading"
                    @click="goToPage(pagination.current_page + 1)"
                >
                    Next
                </button>
            </div>
        </template>
    </main>
</template>