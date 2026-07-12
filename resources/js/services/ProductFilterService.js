import { apiGet } from '../api/client'

export const ProductFilterService = {
    async filters(params = {}) {
        const response = await apiGet('/products/filters', params)

        return response.data || response
    },
}