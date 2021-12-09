import PageDispensePrescription from '@/views/pharma/dispense/PageDispensePrescription.vue';
import PageAddDrug from '@/views/pharma/drugs/PageAddDrug.vue';
import PageEditDrug from '@/views/pharma/drugs/PageEditDrug.vue';
import PageFilterDrugs from '@/views/pharma/drugs/PageFilterDrugs.vue';
import PageViewAllDrugs from '@/views/pharma/drugs/PageViewAllDrugs.vue';
import PagePharmaHome from '@/views/pharma/PagePharmaHome.vue';
import PagePharmaStats from '@/views/pharma/PagePharmaStats.vue';
import PageAddTag from '@/views/pharma/tags/PageAddTag.vue';
import PageEditTag from '@/views/pharma/tags/PageEditTag.vue';
import PageViewAllTags from '@/views/pharma/tags/PageViewAllTags.vue';

export const pharmaRoutes = [
    {
        path: '/pharmacy',
        component: PagePharmaHome,
        meta: {
            requiresAuth: true,
            hasAccess: ['ADMIN', 'PHARMACIST'],
        },
        children: [
            {
                path: '/',
                name: 'PagePharmaHome',
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
            {
                path: '/drugs/filter/:tagId',
                name: 'PageFilterDrugs',
                component: PageFilterDrugs,
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
            {
                path: '/tags/edit/:id',
                name: 'PageEditTag',
                component: PageEditTag,
            },

            /* prescriptions / dispense */
            {
                path: '/prescriptions/dispense/:id',
                name: 'PageDispensePrescription',
                component: PageDispensePrescription,
            },

        ],
    },
];
