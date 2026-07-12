import { useSettingsStore } from '../stores/settingsStore'

export function openWhatsApp(message) {
    const settings = useSettingsStore()
    const phone = normalizePhone(settings.whatsappNumber)

    if (!phone) {
        console.warn('WhatsApp number is not configured in settings.')
        return
    }

    const encodedMessage = encodeURIComponent(message)

    window.open(`https://wa.me/${phone}?text=${encodedMessage}`, '_blank')
}

export function productWhatsAppMessage(product) {
    const settings = useSettingsStore()

    return `${settings.whatsappMessage}

SKU: ${product.sku}
NAME: ${product.title}
LINK: ${window.location.origin}/product/${product.slug}`
}

function normalizePhone(phone) {
    return String(phone || '').replace(/[^\d]/g, '')
}
