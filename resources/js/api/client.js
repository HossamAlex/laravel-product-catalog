const API_BASE_URL = '/api/v1'

export async function apiGet(endpoint, params = {}) {
    const query = new URLSearchParams(params).toString()
    const url = query ? `${API_BASE_URL}${endpoint}?${query}` : `${API_BASE_URL}${endpoint}`

    const response = await fetch(url, {
        headers: {
            Accept: 'application/json',
        },
    })

    if (!response.ok) {
        throw new Error('API request failed')
    }

    return await response.json()
}