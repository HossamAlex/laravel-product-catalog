<script setup>
import { onMounted, ref } from 'vue'
import { HomeService } from '../services/HomeService'
import HeroSlider from '../components/home/HeroSlider.vue'
import FeaturedCategories from '../components/home/FeaturedCategories.vue'
import CollectionSection from '../components/home/CollectionSection.vue'

const home = ref(null)
const loading = ref(true)
const error = ref(null)

onMounted(async () => {
    try {
        home.value = await HomeService.getHome()
    } catch (e) {
        error.value = e.message
    } finally {
        loading.value = false
    }
})
</script>

<template>
    <main>
        <div v-if="loading" class="p-8 text-center">
            Loading...
        </div>

        <div v-else-if="error" class="p-8 text-center text-red-500">
            {{ error }}
        </div>

        <template v-else>
            <HeroSlider :banners="home.heroBanners" />
            <FeaturedCategories :categories="home.featuredCategories" />

           <CollectionSection
                v-for="section in home.sections"
                :key="section.id"
                :section="section"
            />
        </template>
    </main>
</template>