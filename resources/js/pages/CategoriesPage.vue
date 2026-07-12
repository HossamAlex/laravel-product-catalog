<script setup>
import { onMounted, ref } from 'vue'
import { CategoryService } from '../services/CategoryService'

const categories = ref([])
const loading = ref(true)
const error = ref(null)

onMounted(async () => {
    try {
        const response = await CategoryService.getCategories()
        categories.value = response.data || response
    } catch (e) {
        error.value = 'Error loading categories.'
    } finally {
        loading.value = false
    }
})
</script>

<template>
    <main class="mx-auto max-w-7xl px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold">Categories</h1>
            <p class="mt-2 text-gray-500">
                Browse products by main category.
            </p>
        </div>

        <p v-if="loading" class="text-center">Loading...</p>

        <p v-else-if="error" class="text-center text-red-500">
            {{ error }}
        </p>

        <div
            v-else-if="categories.length"
            class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4"
        >
            <router-link
                v-for="category in categories"
                :key="category.id"
                :to="`/category/${category.slug}`"
                class="group rounded-3xl bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md"
            >
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100 text-2xl">
                    {{ category.title?.charAt(0) }}
                </div>

                <h2 class="mt-5 text-xl font-bold group-hover:text-black">
                    {{ category.title }}
                </h2>

                <p class="mt-2 text-sm text-gray-500">
                    {{ category.children?.length || 0 }} sub categories
                </p>

                <div
                    v-if="category.children && category.children.length"
                    class="mt-4 flex flex-wrap gap-2"
                >
                    <span
                        v-for="child in category.children.slice(0, 3)"
                        :key="child.id"
                        class="rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-600"
                    >
                        {{ child.title }}
                    </span>

                    <span
                        v-if="category.children.length > 3"
                        class="rounded-full bg-black px-3 py-1 text-xs text-white"
                    >
                        +{{ category.children.length - 3 }}
                    </span>
                </div>
            </router-link>
        </div>

        <p v-else class="text-center text-gray-400">
            No categories found.
        </p>
    </main>
</template>