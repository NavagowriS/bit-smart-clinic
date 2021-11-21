import PagePatientCreate from '@/views/patients/PagePatientCreate';
import PagePatientEdit from '@/views/patients/PagePatientEdit';
import PagePatientsList from '@/views/patients/PagePatientsList';


export const patientsRoutes = [
    {
        path: '/patients',
        name: 'PagePatientsList',
        component: PagePatientsList,
        meta: {
            requiresAuth: true,
            hasAccess: ['ADMIN', 'STAFF'],
        },
    },

    {
        path: '/patients/create',
        name: 'PagePatientCreate',
        component: PagePatientCreate,
        meta: {
            requiresAuth: true,
            hasAccess: ['ADMIN', 'STAFF'],
        },
    },

    {
        path: '/patients/edit/:id',
        name: 'PagePatientEdit',
        component: PagePatientEdit,
        meta: {
            requiresAuth: true,
            hasAccess: ['ADMIN', 'STAFF'],
        },
    },

];
