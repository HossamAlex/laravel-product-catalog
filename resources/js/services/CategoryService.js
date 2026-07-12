import { apiGet } from '../api/client'

export const CategoryService = {
    async getCategories() {
        const response = await apiGet('/categories')
        return response.data || response
    },
    

    async getCategory(slug, params = {}) {
        const response = await apiGet(`/categories/${slug}`, params)

        
        return {
            category: response.category ?? null,
            breadcrumbs: response.breadcrumbs ?? [],
            products: response.data ?? response.products ?? [],
            meta: response.meta ?? null,
        }
    },
}