import { apiGet } from '../api/client'

export const SettingService = {
    async getSettings() {
        const response = await apiGet('/settings')
        return response.settings || response.data?.settings || {}
    },
}