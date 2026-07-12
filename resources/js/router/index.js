import { createRouter, createWebHistory } from 'vue-router'

import HomePage from '../pages/HomePage.vue'
import ProductsPage from '../pages/ProductsPage.vue'
import ProductDetailsPage from '../pages/ProductDetailsPage.vue'
import CategoriesPage from '../pages/CategoriesPage.vue'
import CategoryPage from '../pages/CategoryPage.vue'
import CollectionPage from '../pages/CollectionPage.vue'
import SearchPage from '../pages/SearchPage.vue'

const routes = [
    {
        path: '/',
        name: 'home',
        component: HomePage,
    },
    {
        path: '/products',
        name: 'products',
        component: ProductsPage,
    },
    {
        path: '/product/:slug',
        name: 'product.details',
        component: ProductDetailsPage,
    },
    {
        path: '/category/:slug',
        name: 'category.details',
        component: CategoryPage,
    },
    {
        path: '/categories',
        name: 'categories',
        component: CategoriesPage,
    },
    {
        path: '/collection/:slug',
        name: 'collection.details',
        component: CollectionPage,
    },
    {
        path: '/search',
        name: 'search',
        component: SearchPage,
    },
    
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router