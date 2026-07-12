import { apiGet } from '../api/client'

export const SearchService = {
    async search(keyword) {
        const response = await apiGet('/search', { q: keyword })

        return {
            products: response.data?.products || [],
            categories: response.data?.categories || [],
            brands: response.data?.brands || [],
        }
    },
}