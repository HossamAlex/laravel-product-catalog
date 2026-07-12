<script setup>
import { ref, watch, nextTick } from 'vue'
import { useRouter } from 'vue-router'
import { SearchService } from '../../services/SearchService'

const props = defineProps({
    open: {
        type: Boolean,
        default: false,
    },
})

const emit = defineEmits(['close'])

const router = useRouter()

const keyword = ref('')
const loading = ref(false)
const products = ref([])
const categories = ref([])
const brands = ref([])
const inputRef = ref(null)

let timer = null


const submitSearch = () => {
    if (!keyword.value || keyword.value.length < 2) return

    const query = keyword.value

    close()

    router.push({
        path: '/search',
        query: { q: query },
    })
}

watch(
    () => props.open,
    async (value) => {
        if (value) {
            await nextTick()
            inputRef.value?.focus()
        }
    }
)

watch(keyword, (value) => {
    clearTimeout(timer)

    if (!value || value.length < 2) {
        products.value = []
        categories.value = []
        brands.value = []
        return
    }

    timer = setTimeout(async () => {
        loading.value = true

        const response = await SearchService.search(value)

        products.value = response.products
        categories.value = response.categories
        brands.value = response.brands

        loading.value = false
    }, 300)
})

const close = () => {
    keyword.value = ''
    emit('close')
}

const goTo = (path) => {
    close()
    router.push(path)
}

</script>

<template>
    <div
        v-if="open"
        class="fixed inset-0 z-[999] bg-white"
    >
        <div class="mx-auto max-w-4xl px-4 py-6">
            <div class="flex items-center gap-3">
                <input
                    ref="inputRef"
                    v-model="keyword"
                    type="text"
                    placeholder="Search for a product, category, or brand..."
                    class="w-full rounded-full border px-5 py-3 outline-none focus:border-black"
                    @keydown.enter="submitSearch"
                />

                <button
                    type="button"
                    class="rounded-full bg-black px-5 py-3 text-white"
                    @click="close"
                >
                   close
                </button>
            </div>

            <div class="mt-8">
                <p v-if="loading" class="text-center text-gray-400">
                    جاري البحث...
                </p>

                <p
                    v-else-if="keyword.length >= 2 && !products.length && !categories.length && !brands.length"
                    class="text-center text-gray-400"
                >
                    لا توجد نتائج
                </p>

                <div v-else class="space-y-8">
                    <section v-if="products.length">
                        <h3 class="mb-3 font-bold">products</h3>

                        <div class="space-y-3">
                            <button
                                v-for="product in products"
                                :key="product.id"
                                type="button"
                                class="flex w-full items-center gap-3 rounded-2xl border p-3 text-right"
                                @click="goTo(`/product/${product.slug}`)"
                            >
                                <img
                                    v-if="product.image"
                                    :src="product.image"
                                    class="h-16 w-16 rounded-xl object-cover"
                                />

                                <div>
                                    <p class="font-semibold">{{ product.title }}</p>
                                    <p class="text-sm text-gray-400">{{ product.sku }}</p>
                                </div>
                            </button>
                        </div>
                    </section>

                    <section v-if="categories.length">
                        <h3 class="mb-3 font-bold">Categories</h3>

                        <div class="flex flex-wrap gap-2">
                            <button
                                v-for="category in categories"
                                :key="category.id"
                                type="button"
                                class="rounded-full bg-gray-100 px-4 py-2"
                                @click="goTo(`/category/${category.slug}`)"
                            >
                                {{ category.title }}
                            </button>
                        </div>
                    </section>

                    <section v-if="brands.length">
                        <h3 class="mb-3 font-bold">Brands</h3>

                        <div class="flex flex-wrap gap-2">
                            <button
                                v-for="brand in brands"
                                :key="brand.id"
                                type="button"
                                class="rounded-full bg-gray-100 px-4 py-2"
                                @click="goTo(`/brand/${brand.slug}`)"
                            >
                                {{ brand.title }}
                            </button>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</template>