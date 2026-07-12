import { apiGet } from '../api/client'

export const HomeService = {
    async getHome() {
        const response = await apiGet('/home')

        return {
            heroBanners: response.data.hero_banners || [],
            featuredCategories: response.data.featured_categories || [],
            sections: response.data.sections || [],
            settings: response.data.settings || {},
        }
    },
}