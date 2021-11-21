import PageAddDrug from '@/views/pharma/drugs/PageAddDrug.vue';
import PageEditDrug from '@/views/pharma/drugs/PageEditDrug.vue';
import PageViewAllDrugs from '@/views/pharma/drugs/PageViewAllDrugs.vue';
import PagePharmaHome from '@/views/pharma/PagePharmaHome.vue';
import PagePharmaStats from '@/views/pharma/PagePharmaStats.vue';
import PageAddTag from '@/views/pharma/tags/PageAddTag.vue';
import PageViewAllTags from '@/views/pharma/tags/PageViewAllTags.vue';

export const pharmaRoutes = [
    {
        path: '/pharmacy',
        name: 'PagePharmaHome',
        component: PagePharmaHome,
        meta: {
            requiresAuth: true,
            hasAccess: ['ADMIN', 'PHARMACIST'],
        },
        children: [
            {
                path: '/',
                name: 'PagePharmaStats',
                component: PagePharmaStats,
            },

            /* drugs pages */
            {
                path: '/drugs',
                name: 'PageViewAllDrugs',
                component: PageViewAllDrugs,
            },
            {
                path: '/drugs/add',
                name: 'PageAddDrug',
                component: PageAddDrug,
            },
            {
                path: '/drugs/edit/:id',
                name: 'PageEditDrug',
                component: PageEditDrug,
            },

            /* tags pages */
            {
                path: '/tags',
                name: 'PageViewAllTags',
                component: PageViewAllTags,
            },
            {
                path: '/tags/add',
                name: 'PageAddTag',
                component: PageAddTag,
            },
        ],
    },
];