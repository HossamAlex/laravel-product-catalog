<script setup>
import { ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import { ProductService } from '../services/ProductService'
import ProductCard from '../components/product/ProductCard.vue'
import ProductGallery from '../components/product/ProductGallery.vue'
import { openWhatsApp, productWhatsAppMessage } from '../utils/whatsapp'
import { useSelectionStore } from '../stores/selectionStore'

const route = useRoute()
const selection = useSelectionStore()

const product = ref(null)
const relatedProducts = ref([])
const breadcrumbs = ref([])
const loading = ref(true)
const error = ref(null)

const loadProduct = async () => {
    loading.value = true
    error.value = null

    try {
        const response = await ProductService.getProduct(route.params.slug)

        product.value = response.product
        relatedProducts.value = response.related_products || []
        breadcrumbs.value = response.breadcrumbs || []
    } catch (e) {
        error.value = 'Error loading product.'
    } finally {
        loading.value = false
    }
}

const askWhatsapp = () => {
    openWhatsApp(productWhatsAppMessage(product.value))
}

const addToSelection = () => {
    selection.add(product.value)
}

watch(
    () => route.params.slug,
    () => {
        loadProduct()
        window.scrollTo({ top: 0, behavior: 'smooth' })
    },
    { immediate: true }
)
</script>

<template>
    <main class="mx-auto max-w-7xl px-4 py-8">
        <p v-if="loading" class="text-center">Loading...</p>

        <p v-else-if="error" class="text-center text-red-500">
            {{ error }}
        </p>

        <template v-else-if="product">
            <nav class="mb-6 text-sm text-gray-500">
                <router-link to="/" class="hover:text-black">Home</router-link>

                <template v-for="item in breadcrumbs" :key="item.id">
                    <span class="mx-2">/</span>
                    <router-link :to="`/category/${item.slug}`" class="hover:text-black">
                        {{ item.title }}
                    </router-link>
                </template>

                <span class="mx-2">/</span>
                <span class="text-gray-900">{{ product.title }}</span>
            </nav>

            <section class="grid gap-8 lg:grid-cols-2">
                <div class="space-y-6">
                    <ProductGallery :product="product" />

                    <section
                        v-if="product.videos && product.videos.length"
                        class="rounded-3xl bg-white p-4"
                    >
                        <h3 class="mb-4 text-lg font-bold">Product Videos</h3>

                        <div class="grid gap-4">
                            <video
                                v-for="video in product.videos"
                                :key="video"
                                controls
                                playsinline
                                preload="metadata"
                                class="aspect-video w-full rounded-2xl bg-black object-cover"
                            >
                                <source :src="video" type="video/mp4" />
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    </section>
                </div>

                <aside class="h-fit rounded-3xl bg-white p-6 shadow-sm lg:sticky lg:top-24">
                    <p class="text-sm text-gray-400">{{ product.sku }}</p>

                    <h1 class="mt-2 text-3xl font-bold leading-tight">
                        {{ product.title }}
                    </h1>

                    <router-link
                        v-if="product.brand"
                        :to="`/brand/${product.brand.slug}`"
                        class="mt-2 inline-block text-sm text-gray-500 hover:text-black"
                    >
                        {{ product.brand.title }}
                    </router-link>

                    <div class="mt-6 flex items-end gap-3">
                        <span v-if="product.special_price" class="text-3xl font-bold">
                            {{ product.special_price }} KD
                        </span>

                        <span
                            v-if="product.special_price && product.price"
                            class="text-base text-gray-400 line-through"
                        >
                            {{ product.price }} KD
                        </span>

                        <span v-else-if="product.price" class="text-3xl font-bold">
                            {{ product.price }} KD
                        </span>
                    </div>

                    <p
                        v-if="product.description"
                        class="mt-6 leading-8 text-gray-600"
                    >
                        {{ product.description }}
                    </p>

                    <div
                        v-if="product.categories && product.categories.length"
                        class="mt-6"
                    >
                        <h3 class="mb-3 font-bold">Categories</h3>

                        <div class="flex flex-wrap gap-2">
                            <router-link
                                v-for="category in product.categories"
                                :key="category.id"
                                :to="`/category/${category.slug}`"
                                class="rounded-full bg-gray-100 px-4 py-2 text-sm hover:bg-black hover:text-white"
                            >
                                {{ category.title }}
                            </router-link>
                        </div>
                    </div>

                    <div class="mt-8 space-y-3">
                        <button
                            type="button"
                            class="w-full rounded-full bg-black py-4 font-semibold text-white"
                            @click="askWhatsapp"
                        >
                            Inquiry via WhatsApp
                        </button>

                        <button
                            type="button"
                            class="w-full rounded-full border border-black py-4 font-semibold text-black"
                            @click="addToSelection"
                        >
                            Add to My Selection
                        </button>
                    </div>
                </aside>
            </section>

            <section v-if="relatedProducts.length" class="mt-16">
                <div class="mb-5 flex items-center justify-between">
                    <h2 class="text-xl font-bold">Related Products</h2>
                </div>

                <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                    <ProductCard
                        v-for="item in relatedProducts"
                        :key="item.id"
                        :product="item"
                    />
                </div>
            </section>
        </template>
    </main>
</template>