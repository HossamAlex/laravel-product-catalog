<script setup>
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Autoplay, Pagination } from 'swiper/modules'

import 'swiper/css'
import 'swiper/css/pagination'

defineProps({
    banners: {
        type: Array,
        default: () => [],
    },
})

const modules = [Autoplay, Pagination]
</script>

<template>
    <section class="mx-auto max-w-7xl px-4 pt-6">
        <Swiper
            v-if="banners.length"
            :modules="modules"
            :slides-per-view="1"
            :loop="banners.length > 1"
            :autoplay="{
                delay: 3500,
                disableOnInteraction: false,
            }"
            :pagination="{ clickable: true }"
            class="overflow-hidden rounded-3xl bg-white"
        >
            <SwiperSlide
                v-for="banner in banners"
                :key="banner.id"
            >
                <a
                    :href="banner.link || '#'"
                    class="relative block"
                >
                    <img
                        :src="banner.mobile_image || banner.desktop_image"
                        :alt="banner.title || 'Banner'"
                        class="h-64 w-full object-cover md:h-96"
                    />

                    <div
                        v-if="banner.title || banner.button_text"
                        class="absolute inset-0 flex items-end bg-gradient-to-t from-black/50 to-transparent p-6"
                    >
                        <div class="text-white">
                            <h2
                                v-if="banner.title"
                                class="text-2xl font-bold"
                            >
                                {{ banner.title }}
                            </h2>

                            <span
                                v-if="banner.button_text"
                                class="mt-3 inline-block rounded-full bg-white px-5 py-2 text-sm font-bold text-black"
                            >
                                {{ banner.button_text }}
                            </span>
                        </div>
                    </div>
                </a>
            </SwiperSlide>
        </Swiper>

        <div
            v-else
            class="flex h-64 items-center justify-center rounded-3xl bg-white text-gray-400"
        >
            لا يوجد بانر حالياً
        </div>
    </section>
</template>