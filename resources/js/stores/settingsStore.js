import { defineStore } from 'pinia'
import { SettingService } from '../services/SettingService'

export const useSettingsStore = defineStore('settings', {
    state: () => ({
        values: {},
        loaded: false,
        loading: false,
        error: null,
    }),

    getters: {
        siteName: (state) => state.values.site_name || 'Laravel Product Catalog',
        siteLogo: (state) => state.values.site_logo || null,
        siteFavicon: (state) => state.values.site_favicon || null,
        websiteUrl: (state) => state.values.website_url || window.location.origin,
        whatsappNumber: (state) => state.values.whatsapp_number || null,
        whatsappMessage: (state) => (
            state.values.whatsapp_message
            || 'Hello, I would like to inquire about your products.'
        ),
    },

    actions: {
        async load(force = false) {
            if (this.loaded && !force) return this.values
            if (this.loading) return this.values

            this.loading = true
            this.error = null

            try {
                this.values = await SettingService.getSettings()
                this.loaded = true
                return this.values
            } catch (error) {
                this.error = error.message || 'Unable to load settings.'
                throw error
            } finally {
                this.loading = false
            }
        },
    },
})
