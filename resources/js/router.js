import { createRouter, createWebHashHistory } from 'vue-router'

const router = createRouter({
  history: createWebHashHistory(),
  routes: [
    {
      path: '/',
      redirect: () => ({ name: 'product', params: { slug: 'fall-limited-edition-sneakers' } })
    },
    {
      path: '/shop/product/:slug',
      name: 'product',
      component: () => import('./views/Product.vue')
    },
    {
      path: '/not-found',
      name: 'notFound',
      component: () => import('./views/NotFound.vue')
    },
    {
      path: '/:pathMatch(.*)*',
      redirect: '/not-found'
    }
  ],
})

export default router;