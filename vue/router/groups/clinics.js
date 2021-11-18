import PageListAppointments from '@/views/clinics/appointments/PageListAppointments.vue';
import PageSingleAppointment from '@/views/clinics/appointments/PageSingleAppointment.vue';
import PageClinicPatientDetails from '@/views/clinics/clinic_patients/PageClinicPatientDetails.vue';
import PageClinicVisitManage from '@/views/clinics/clinic_visits/PageClinicVisitManage';
import PageClinicVisitPatientDetails from '@/views/clinics/clinic_visits/PageClinicVisitPatientDetails';
import PageClinicCreate from '@/views/clinics/PageClinicCreate';
import PageClinicManage from '@/views/clinics/PageClinicManage';
import PageClinicsList from '@/views/clinics/PageClinicsList';


export const clinicsRoutes = [
    {
        path: '/clinics',
        name: 'pageClinicsList',
        component: PageClinicsList,
        meta: {
            requiresAuth: true,
            hasAccess: ['ADMIN', 'STAFF'],
        },
    },
    {
        path: '/clinics/create',
        name: 'pageClinicCreate',
        component: PageClinicCreate,
        meta: {
            requiresAuth: true,
            hasAccess: ['ADMIN', 'STAFF'],
        },
    },
    {
        path: '/clinics/:id',
        name: 'pageClinic',
        component: PageClinicManage,
        meta: {
            requiresAuth: true,
            hasAccess: ['ADMIN', 'STAFF', 'DOCTOR'],
        },
    },
    {
        path: '/clinics/:clinicId/appointments',
        name: 'pageListAppointments',
        component: PageListAppointments,
        meta: {
            requiresAuth: true,
            hasAccess: ['ADMIN', 'STAFF', 'DOCTOR'],
        },
    },
    {
        path: '/clinics/:clinicId/appointments/:appointmentId',
        name: 'pageSingleAppointment',
        component: PageSingleAppointment,
        meta: {
            requiresAuth: true,
            hasAccess: ['ADMIN', 'STAFF', 'DOCTOR'],
        },
    },
    {
        path: '/clinics/:clinicId/visits/:visitId',
        name: 'pageClinicVisitManage',
        component: PageClinicVisitManage,
        meta: {
            requiresAuth: true,
            hasAccess: ['ADMIN', 'STAFF', 'DOCTOR'],
        },
    },
    {
        path: '/clinics/:clinicId/visits/:visitId/patients/:clinicVisitPatientId',
        name: 'pageClinicVisitDetails',
        component: PageClinicVisitPatientDetails,
        meta: {
            requiresAuth: true,
            hasAccess: ['ADMIN', 'STAFF', 'DOCTOR'],
        },
    },
    {
        path: '/clinics/:clinicId/patients/:clinicVisitPatientId',
        name: 'pageClinicPatientDetails',
        component: PageClinicPatientDetails,
        meta: {
            requiresAuth: true,
            hasAccess: ['ADMIN', 'STAFF', 'DOCTOR'],
        },
    },


];
