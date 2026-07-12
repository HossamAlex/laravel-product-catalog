<script setup>
defineProps({
    categories: {
        type: Array,
        default: () => [],
    },
})
</script>

<template>
    <section class="mx-auto mt-10 max-w-7xl px-4">
        <h2 class="mb-4 text-xl font-bold">Shop By Category</h2>

        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
            <router-link
                v-for="category in categories"
                :key="category.id"
                :to="`/category/${category.slug}`"
                class="rounded-2xl bg-white p-5 text-center shadow-sm"
            >
                <span class="font-semibold">{{ category.title }}</span>
            </router-link>
        </div>
    </section>
</template>