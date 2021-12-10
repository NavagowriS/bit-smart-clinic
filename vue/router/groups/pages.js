import PageDoctorHome from '@/views/pages/PageDoctorHome.vue';
import About from '../../views/pages/About';
import Home from '../../views/pages/Home';

export const pagesRoutes = [

    {
        path: '/',
        name: 'home',
        component: Home,
        meta: {
            requiresAuth: true,
            hasAccess: ['ADMIN', 'STAFF', 'DOCTOR', 'PHARMACIST'],
        },
    },
    {
        path: '/home/doctor',
        name: 'pageDoctorHome',
        component: PageDoctorHome,
        meta: {
            requiresAuth: true,
            hasAccess: ['ADMIN', 'DOCTOR'],
        },
    },
    {
        path: '/about',
        name: 'about',
        component: About,
        meta: {
            requiresAuth: true,
            hasAccess: ['ADMIN', 'STAFF'],
        },
    },
];
