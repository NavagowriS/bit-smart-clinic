import BookAppointment from '@/public_patient_views/BookAppointment.vue';
import PagePatientHome from '@/public_patient_views/PagePatientHome.vue';
import PageViewAppointment from '@/public_patient_views/PageViewAppointment.vue';
import PatientLogin from '@/public_patient_views/PatientLogin.vue';

export const publicPatientsRoutes = [
    {
        path: '/patient-login',
        name: 'PagePatientLogin',
        component: PatientLogin,
    },
    {
        path: '/public/patient',
        name: 'PagePatientHome',
        component: PagePatientHome,
    },
    {
        path: '/public/patient/book-appointment/:clinicPatientId',
        name: 'PageBookAppointment',
        component: BookAppointment,
    },
    {
        path: '/public/patient/appointment/:clinicId/:appointmentId',
        name: 'PageViewAppointment',
        component: PageViewAppointment,
    },
];
