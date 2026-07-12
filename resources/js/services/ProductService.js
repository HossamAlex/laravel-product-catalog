import { apiGet } from '../api/client'

export const ProductService = {

    async getProducts(params = {}) {
        return await apiGet('/products', params)
    },

    async getProduct(slug) {
        const response = await apiGet(`/products/${slug}`)

        return {
            product: response.data?.product || null,
            breadcrumbs: response.data?.breadcrumbs || [],
            related_products: response.data?.related_products || [],
        }
    },

}