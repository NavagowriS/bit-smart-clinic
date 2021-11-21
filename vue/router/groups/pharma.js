import PagePharmaHome from '@/views/pharma/PagePharmaHome.vue';

export const pharmaRoutes = [
    {
        path: '/pharmacy',
        name: 'PagePharmaHome',
        component: PagePharmaHome,
        meta: {
            requiresAuth: true,
            hasAccess: ['ADMIN', 'PHARMACIST'],
        },
    },
];
