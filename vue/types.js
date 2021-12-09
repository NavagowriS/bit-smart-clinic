/**
 * @typedef {Object} User
 * @property {number} id
 * @property {string} username
 * @property {string} email
 * @property {string} full_name
 * @property {string} role
 */

/**
 * @typedef {Object} Patient
 *
 * @property {number} id
 * @property {string} full_name
 * @property {number} age
 * @property {string} phone
 * @property {string} nic
 * @property {string} address
 * @property {string} guardian_name
 * @property {string} guardian_phone
 * @property {string} gender
 * @property {string} dob
 * @property {string} login_name
 * @property {string} login_pass
 */

/**
 * @typedef {Object} DoctorSpeciality
 * @property {number} id
 * @property {string} speciality
 */

/**
 * @typedef {Object} Doctor
 * @property {number} id
 * @property {number} speciality_id
 * @property {string} name
 * @property {string} email
 * @property {string} phone
 * @property {string} dob
 * @property {DoctorSpeciality} doctor_speciality
 * @property {number} user_id
 * @property {User} user
 * @property {Clinic[]} clinics
 */


/**
 * @typedef {Object} Clinic
 *
 * @property {number} id
 * @property {number} doctor_in_charge_id
 * @property {string} title
 * @property {number} clinicPatientsCount
 * @property {Doctor} doctor_in_charge
 */

/**
 * @typedef {Object} ClinicVisit
 * @property {number} id
 * @property {number} clinic_id
 * @property {string} visit_date
 */


/**
 * @typedef {Object} ClinicPatient
 *
 * @property {number} id
 * @property {number} clinic_id
 * @property {number} patient_id
 * @property {string} since
 * @property {Patient} patient
 * @property {Clinic} clinic
 * @property {ClinicVisitPatient[]} visit_details
 */

/**
 * @typedef {Object} ClinicPatientsList
 * @property {Clinic} clinic
 * @property {ClinicPatient[]} patients
 */

/**
 * @typedef {Object} ClinicVisit
 * @property {number} id
 * @property {number} clinic_id
 * @property {number} visit_date
 * @property {Clinic} clinic
 */

/**
 * @typedef {Object} ClinicAppointment
 * @property {number} id
 * @property {number} clinic_id
 * @property {number} clinic_patient_id
 * @property {ClinicPatient} clinic_patient
 * @property {string} clinic_date
 * @property {Clinic} clinic
 * @property {number} token_number
 * @property {string} status
 * @property {number} param_weight
 * @property {number} param_height
 * @property {number} param_sbp
 * @property {number} param_dbp
 * @property {number} param_blood_sugar
 * @property {string} doctor_remarks
 */


/**
 * @typedef {Object} ClinicVisitPatient
 * @property {number} id
 * @property {number} clinic_patient_id
 * @property {number} clinic_visit_id
 * @property {number} token_number
 * @property {string} status
 * @property {number} param_weight
 * @property {number} param_height
 * @property {number} param_sbp
 * @property {number} param_dbp
 * @property {number} param_blood_sugar
 * @property {string} doctor_remarks
 * @property {string} visit_date
 * @property {ClinicVisit} clinic_visit
 * @property {ClinicPatient} clinic_patient
 */


/**
 * @typedef {Object} ClinicAndVisits
 * @property {Clinic} clinic
 * @property {ClinicVisitPatient[]} visitDetails
 * @property {ClinicPatient} clinicPatient
 * @property {ClinicAppointment[]} appointments
 */


/* --------------------------------------------------- */

/**
 * @typedef {Object} Drug
 * @property {number} id
 * @property {string} drug_name
 * @property {string} generic_name
 * @property {string} brand_name
 * @property {DrugTag[]} drugTags
 * @property {number} total_count
 * @property {number} min_quantity
 */

/**
 * @typedef {Object} PharmacyTag
 * @property {number} id
 * @property {string} tag
 */

/**
 * @typedef {Object} DrugTag
 * @property {number} id
 * @property {number} drug_id
 * @property {number} tag_id
 * @property {PharmacyTag} tag
 * @property {Drug} drug
 */

/**
 * @typedef {Object} Prescription
 * @property {number} id
 * @property {number} appointment_id
 * @property {string} prescription_date
 * @property {string} remarks
 * @property {string} status
 * @property {ClinicAppointment} appointment
 */


/**
 * @typedef {Object} PrescriptionItem
 * @property {number} id
 * @property {number} prescription_id
 * @property {number} drug_id
 * @property {number} dose
 * @property {string} frequency
 * @property {number} period
 * @property {string} remarks
 * @property {number} total_count
 * @property {Drug} drug
 */
