<script setup>
import { onMounted, ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import { CategoryService } from '../services/CategoryService'
import ProductCard from '../components/product/ProductCard.vue'
import ProductFilters from '../components/filters/ProductFilters.vue'

const route = useRoute()

const category = ref(null)
const products = ref([])
const breadcrumbs = ref([])
const currentFilters = ref({})
const pagination = ref(null)
const page = ref(1)
const perPage = 8

const loading = ref(true)
const error = ref(null)

const loadCategory = async () => {
    loading.value = true
    error.value = null

    try {
        const response = await CategoryService.getCategory(route.params.slug, {
            ...currentFilters.value,
            page: page.value,
            per_page: perPage,
        })

        category.value = response.category
        breadcrumbs.value = response.breadcrumbs
        products.value = response.products
        pagination.value = response.meta || null
    } catch (e) {
        error.value = 'An error occurred while loading the section.'
    } finally {
        loading.value = false
    }
}

const onFilterChange = (filters) => {
    currentFilters.value = filters
    page.value = 1
    loadCategory()
}

const goToPage = (newPage) => {
    if (!pagination.value) return
    if (newPage < 1 || newPage > pagination.value.last_page) return

    page.value = newPage
    loadCategory()

    window.scrollTo({
        top: 0,
        behavior: 'smooth',
    })
}

onMounted(loadCategory)

watch(
    () => route.params.slug,
    () => {
        category.value = null
        products.value = []
        breadcrumbs.value = []
        currentFilters.value = {}
        pagination.value = null
        page.value = 1
        loadCategory()
    }
)
</script>

<template>
    <main class="mx-auto max-w-7xl px-4 py-8">
        <p v-if="loading && !category">Loading...</p>

        <p v-else-if="error" class="text-center text-red-500">
            {{ error }}
        </p>

        <template v-else-if="category">
            <nav class="mb-4 text-sm text-gray-500">
                <router-link to="/" class="hover:text-black">Home</router-link>

                <template v-for="item in breadcrumbs" :key="item.id">
                    <span class="mx-2">/</span>
                    <router-link :to="`/category/${item.slug}`" class="hover:text-black">
                        {{ item.title }}
                    </router-link>
                </template>
            </nav>

            <h1 class="mb-6 text-2xl font-bold">{{ category.title }}</h1>

            <div
                v-if="category.children && category.children.length"
                class="scrollbar-hidden mb-8 flex snap-x gap-3 overflow-x-auto pb-2 md:grid md:grid-cols-4 md:overflow-visible md:pb-0"
            >
                <router-link
                    v-for="child in category.children"
                    :key="child.id"
                    :to="`/category/${child.slug}`"
                    class="min-w-max snap-start rounded-full bg-white px-5 py-2.5 text-sm font-medium text-gray-800 shadow-sm md:rounded-2xl md:px-4 md:py-3 md:text-center"
                    active-class="!bg-blue-600 !text-white"
                >
                    {{ child.title }}
                </router-link>
            </div>

            <ProductFilters
                :category-slug="route.params.slug"
                :selected-brand="currentFilters.brand || ''"
                @change="onFilterChange"
            />

            <div class="mb-5 flex items-center justify-between">
                <h2 class="text-xl font-bold">Products</h2>

                <span v-if="pagination" class="text-sm text-gray-500">
                    {{ pagination.total }} Product(s)
                </span>
            </div>

            <p v-if="loading" class="mb-4 text-center text-gray-400">
                Products are being updated...
            </p>

            <div
                v-if="products.length"
                class="grid grid-cols-2 gap-4 md:grid-cols-4"
            >
                <ProductCard
                    v-for="product in products"
                    :key="product.id"
                    :product="product"
                />
            </div>

            <p v-else-if="!loading" class="text-center text-gray-400">
                No products found in this section
            </p>

            <div
                v-if="pagination && pagination.last_page > 1"
                class="scrollbar-hidden mt-10 flex items-center justify-center gap-2 overflow-x-auto pb-2"
            >
                <button
                    type="button"
                    class="rounded-full border px-4 py-2 disabled:opacity-40"
                    :disabled="pagination.current_page === 1 || loading"
                    @click="goToPage(pagination.current_page - 1)"
                >
                    Previous
                </button>

                <button
                    v-for="pageNumber in pagination.last_page"
                    :key="pageNumber"
                    type="button"
                    class="h-10 min-w-10 shrink-0 rounded-full border px-3 text-sm"
                    :class="pageNumber === pagination.current_page
                        ? 'border-black bg-black text-white'
                        : 'bg-white text-gray-700'"
                    :disabled="loading"
                    @click="goToPage(pageNumber)"
                >
                    {{ pageNumber }}
                </button>

                <button
                    type="button"
                    class="rounded-full border px-4 py-2 disabled:opacity-40"
                    :disabled="pagination.current_page === pagination.last_page || loading"
                    @click="goToPage(pagination.current_page + 1)"
                >
                    Next
                </button>
            </div>
        </template>
    </main>
</template>
