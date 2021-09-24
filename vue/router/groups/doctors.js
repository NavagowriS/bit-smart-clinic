import PageDoctorCreate from '@/views/doctors/PageDoctorCreate';
import PageDoctorEdit from '@/views/doctors/PageDoctorEdit';
import PageDoctorsList from '@/views/doctors/PageDoctorsList';


export const doctorsRoutes = [
    {
        path: '/doctors',
        component: PageDoctorsList,
        meta: {
            requiresAuth: true,
            hasAccess: ['ADMIN', 'STAFF'],
        },
    },
    {
        path: '/doctors/create',
        component: PageDoctorCreate,
        meta: {
            requiresAuth: true,
            hasAccess: ['ADMIN'],
        },
    },
    {
        path: '/doctors/edit/:id',
        component: PageDoctorEdit,
        meta: {
            requiresAuth: true,
            hasAccess: ['ADMIN', 'STAFF'],
        },
    },
];
