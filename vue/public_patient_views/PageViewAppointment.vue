<template>
	
	<div class="container my-3" v-if="loaded">

    <LogOut/>

		<div class="row">
			<div class="col">
				
				<CardSection class="mb-3">
					
					<div class="section__patient_details d-flex justify-content-between">
						
						<div class="d-flex gap-5">
							<div class="d-flex flex-column align-items-center gap-2 bg-dark text-white p-3 rounded">
								<div class="lead">TOKEN</div>
								<div class="token_number fw-bold">
									{{ appointment.token_number }}
								</div>
							</div>
							
							<div class="d-flex flex-column">
								<h2 class="">{{ patient.full_name }}</h2>
								<div class="lead">DoB: {{ patient.dob }} ({{ patient.age }})</div>
								<div class="">Gender: {{ patient.gender }}</div>
							</div>
						
						</div>
						
						
						<div class="d-flex flex-column">
							<h4>Phone: {{ patient.phone }}</h4>
							<div class="lead">NIC: {{ patient.nic }}</div>
							<div class="">{{ patient.address }}</div>
							<hr>
							<div class="">Guardian: {{ patient.guardian_name }}</div>
							<div class="">Phone: {{ patient.guardian_phone }}</div>
						</div>
					
					</div>
					<!-- section: patient_details -->
				</CardSection>
				
				
				<!-- CARD: Visit details -->
				<CardSection class="mb-3">
					
					<template v-slot:header>Visit details</template>
					
					<div class="section__visit_details">
						
						<div class="d-flex flex-column">
							
							<div class="visit_status d-flex gap-2 mb-3">
								<div class="lead fw-bold">STATUS:</div>
								<div class="lead fw-bold" :style="statusColorClass">{{ appointment.status }}</div>
							</div>
							<!-- visit status -->
							
							<div class="visit_params d-flex gap-3">
								
								<div class="mb-3">
									<label class="form-label">Weight (kg)</label>
									<input type="number" class="form-control" v-model="appointment.param_weight" disabled>
								</div>
								
								<div class="mb-3">
									<label class="form-label">Height (m)</label>
									<input type="number" class="form-control" v-model="appointment.param_height" disabled>
								</div>
								
								
								<div class="mb-3">
									<label class="form-label">SBP (mmHg)</label>
									<input type="number" class="form-control" v-model="appointment.param_sbp" disabled>
								</div>
								
								
								<div class="mb-3">
									<label class="form-label">DBP (mmHg)</label>
									<input type="number" class="form-control" v-model="appointment.param_dbp" disabled>
								</div>
								
								<div class="mb-3">
									<label class="form-label">Sugar Level (mg/dL)</label>
									<input type="number" class="form-control" v-model="appointment.param_blood_sugar" disabled>
								</div>
							
							</div>
							<!-- visit params -->
							
							<div class="visit_doctor_remarks">
								<div class="mb-3">
									<label class="form-label">Doctor remarks</label>
									<textarea rows="10" class="form-control" v-model="appointment.doctor_remarks" disabled></textarea>
								</div>
							</div>
						
						</div>
					
					
					</div>
					<!-- section: visit details -->
				
				</CardSection>
				<!-- CARD: Visit details -->
				
				
				<div class="row mb-3" v-if="prescription">
					<div class="col">
						<CardSection>
							<template v-slot:header>Prescription</template>
							
							<div class="">
								
								<div class="row">
									<div class="col">
										Date: {{ prescription.prescription_date }}
										<span v-if="prescription.remarks">| {{ prescription.remarks }}</span>
									</div>
								</div><!-- row -->
								
								<table class="table table-striped table-bordered mb-3" v-if="prescriptionItems.length > 0">
									<thead>
									<tr>
										<th>Drug</th>
										<th style="width: 120px">Dose</th>
										<th style="width: 120px">Frequency</th>
										<th style="width: 120px">Period (Days)</th>
										<th>Remarks</th>
									</tr>
									</thead>
									<tbody>
									<tr v-for="item in prescriptionItems">
										<td>{{ item.drug.drug_name }}</td>
										<td>
											<input type="number"
														 class="form-control form-control-sm text-end"
														 min="1"
														 v-model.number="item['dose']" disabled>
										</td>
										<td>
											<input type="text"
														 class="form-control form-control-sm text-end"
														 min="1"
														 v-model="item['frequency']" disabled>
										</td>
										<td>
											<input type="number"
														 class="form-control form-control-sm text-end"
														 min="1"
														 v-model.number="item['period']" disabled>
										</td>
										<td>
											<input type="text"
														 class="form-control form-control-sm"
														 v-model="item['remarks']" disabled>
										</td>
									</tr>
									</tbody>
								</table>
							
							</div>
						
						</CardSection>
					</div>
				</div><!-- prescriptions -->
			
			
			</div><!-- col -->
		</div><!-- row -->
	</div><!-- container -->

</template>

<script>
import CardSection from '@/components/CardSection.vue';
import _ from 'lodash';
import LogOut from '@/public_patient_views/components/LogOut';

export default {
	name: 'PageViewAppointment',
	components: {LogOut, CardSection },
	
	data() {
		return {
			
			loaded: false,
			
			/* status data */
			status: {
				colorCodes: {
					'ACTIVE': '#3498db',
					'COMPLETED': '#27ae60',
					'CANCELLED': '#f1c40f',
					'MISSED': '#e74c3c',
				},
			},
			
			prescription: null,
			prescriptionItems: [],
			
		};
	},
	
	computed: {
		
		clinicId() {
			return _.toNumber( this.$route.params[ 'clinicId' ] );
		},
		
		appointmentId() {
			return _.toNumber( this.$route.params[ 'appointmentId' ] );
		},
		
		backLink() {
			return {
				name: 'pageListAppointments', params: { id: this.clinicId },
			};
		},
		
		/** @returns {Clinic} */
		clinic() {
			return this.$store.getters[ 'clinics/getClinic' ];
		},
		
		/** @returns {ClinicAppointment} */
		appointment() {
			return this.$store.getters[ 'clinicAppointments/getClinicAppointment' ];
		},
		
		/** @returns {Patient} */
		patient() {
			return this.appointment.clinic_patient.patient;
		},
		
		statusColorClass() {
			return `color: ${ this.status.colorCodes[ this.appointment.status ] }`;
		},
		
	},
	
	async mounted() {
		try {
			
			// fetch clinic details
			await this.$store.dispatch( 'clinics/fetch', this.clinicId );
			
			await this.$store.dispatch( 'clinicAppointments/fetch', this.appointmentId );
			
			this.prescription = await this.$store.dispatch( 'prescriptions/fetchByAppointment', this.appointmentId );
			
			if ( this.prescription ) {
				this.prescriptionItems = await this.$store.dispatch( 'prescriptions/fetchAllPrescriptionItems', this.prescription.id );
			}
			
			this.loaded = true;
			
		} catch ( e ) {
		
		}
	},
	
	methods: {
		//
	},
	
};
</script>

<style scoped>
.token_number {
	font-size: 5rem;
	line-height: 3.5rem;
}
</style>
