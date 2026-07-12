import { defineStore } from 'pinia'
import { ProductService } from '../services/ProductService'

export const useSelectionStore = defineStore('selection', {
    state: () => ({
        items: JSON.parse(localStorage.getItem('viona_selection') || '[]'),
    }),

    getters: {
        count: (state) => state.items.length,
    },

    actions: {
        save() {
            localStorage.setItem('viona_selection', JSON.stringify(this.items))
        },

        add(product) {
            const selectionItem = {
                id: product.id,
                sku: product.sku,
                title: product.title,
                slug: product.slug,
                image: product.image,
                price: product.special_price || product.price,
            }

            const existingItem = this.items.find((item) => item.id === product.id)

            if (existingItem) {
                Object.assign(existingItem, selectionItem)
            } else {
                this.items.push(selectionItem)
            }

            this.save()
        },

        async hydrateMissingImages() {
            const missingImages = this.items.filter((item) => !item.image && item.slug)

            if (!missingImages.length) return

            const results = await Promise.allSettled(
                missingImages.map((item) => ProductService.getProduct(item.slug))
            )

            let changed = false

            results.forEach((result, index) => {
                const product = result.status === 'fulfilled' ? result.value.product : null

                if (!product) return

                Object.assign(missingImages[index], {
                    sku: product.sku,
                    title: product.title,
                    image: product.image,
                    price: product.special_price || product.price,
                })
                changed = true
            })

            if (changed) this.save()
        },

        remove(id) {
            this.items = this.items.filter((item) => item.id !== id)
            this.save()
        },

        clear() {
            this.items = []
            this.save()
        },
    },
})
