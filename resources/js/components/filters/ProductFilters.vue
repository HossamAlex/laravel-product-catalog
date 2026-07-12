<script setup>
import { onMounted, ref, watch } from 'vue'
import { ProductFilterService } from '../../services/ProductFilterService'

const props = defineProps({
    categorySlug: {
        type: String,
        default: '',
    },
    selectedBrand: {
        type: String,
        default: '',
    },
})

const emit = defineEmits(['change'])

const loading = ref(true)

const filters = ref({
    brands: [],
})

const loadFilters = async () => {
    loading.value = true

    const response = await ProductFilterService.filters({
        category: props.categorySlug,
    })

    filters.value.brands = response.brands || []
    loading.value = false
}

const selectBrand = (brandSlug) => {
    emit('change', {
        brand: brandSlug,
    })
}

onMounted(loadFilters)

watch(
    () => props.categorySlug,
    () => {
        loadFilters()
    }
)
</script>

<template>
    <div v-if="loading" class="mb-8 rounded-2xl bg-white p-4 text-gray-400">
       Loading filters...
    </div>

    <div
        v-else-if="filters.brands.length"
        class="mb-8 rounded-2xl bg-white p-4"
    >
        <h3 class="mb-3 font-bold">Brands</h3>

        <div class="scrollbar-hidden -mx-4 flex snap-x gap-2 overflow-x-auto px-4 pb-2 md:mx-0 md:flex-wrap md:overflow-visible md:px-0 md:pb-0">
            <button
                type="button"
                class="shrink-0 snap-start rounded-full border px-4 py-2 text-sm"
                :class="selectedBrand === '' ? 'bg-black text-white' : 'bg-white text-black'"
                @click="selectBrand('')"
            >
                ALL
            </button>

            <button
                v-for="brand in filters.brands"
                :key="brand.id"
                type="button"
                class="shrink-0 snap-start rounded-full border px-4 py-2 text-sm"
                :class="selectedBrand === brand.slug ? 'bg-black text-white' : 'bg-white text-black'"
                @click="selectBrand(brand.slug)"
            >
                {{ brand.title }} ({{ brand.products_count }})
            </button>
        </div>
    </div>
</template>
