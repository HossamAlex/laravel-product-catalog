import { apiGet } from '../api/client'

export const CollectionService = {
    getCollection(slug) {
        return apiGet(`/collections/${slug}`)
    },
}