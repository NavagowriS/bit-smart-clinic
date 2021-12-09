<template>
	
	<div class="">
		
		<CardSection v-if="prescription">
			<template v-slot:header>
				<div class="d-flex justify-content-between">
					<div>Prescription on {{ prescription.prescription_date }}</div>
					<div :class="statusClass">Status: {{ prescription.status }}</div>
				</div>
			</template>
			
			<div class="mb-3 d-flex gap-2">
				<div>
					<span class="fw-bold">Clinic: </span> {{ prescription.appointment.clinic.title }}
				</div>
				<div>
					<span class="fw-bold">Doctor: </span> {{ prescription.appointment.clinic.doctor_in_charge.name }}
				</div>
			</div>
			
			<div class="mb-3 d-flex gap-2">
				<div>
					<span class="fw-bold">Patient: </span> {{ prescription.appointment.clinic_patient.patient.full_name }}
				</div>
				<div class="fw-bold">
					{{ prescription.appointment.clinic_patient.patient.gender }}
				</div>
				<div class="fw-bold">
					{{ prescription.appointment.clinic_patient.patient.age }} Yrs
				</div>
				<div>
					<span class="fw-bold">Token No: </span> {{ prescription.appointment.token_number }}
				</div>
			</div>
			
			<div class="mb-3">
				
				<table class="table table-striped table-bordered mb-3" v-if="prescriptionItems.length > 0">
					<thead>
					<tr>
						<th>Drug</th>
						<th style="width: 120px">Dose</th>
						<th style="width: 120px">Frequency (Per Day)</th>
						<th style="width: 120px">Period (Days)</th>
						<th>Quantity</th>
					</tr>
					</thead>
					<tbody>
					<tr v-for="item in prescriptionItems">
						<td>{{ item.drug.drug_name }}</td>
						<td> {{ item.dose }}</td>
						<td> {{ item.frequency }}</td>
						<td> {{ item.period }}</td>
						<td>
							<input type="number" class="form-control form-control-sm" min="0"
										 :disabled="isCompleted"
										 v-model="item['total_count']"
										 @change="onUpdatePrescriptionItem(item)">
						</td>
					</tr>
					</tbody>
				</table>
				
				<div class="text-center my-3" v-if="itemsUpdated">
              <span class="alert alert-success p-1">
                ✅ Item details updated
              </span>
				</div>
				
				
				<div class="mb-3 text-center">
					<button class="btn btn-success" @click="onDispense()" :disabled="isCompleted">Dispense</button>
					<button class="btn btn-secondary">Print</button>
				</div>
				
				
				<div class="" v-if="isCompleted">
					<button class="btn btn-link text-danger" @click="onSetAsPending()">Set as pending again</button>
				</div>
			
			</div>
		
		</CardSection>
	
	</div>

</template>

<script>
import CardSection from '@/components/CardSection.vue';
import {showErrorDialog} from '@/helpers/common.js';
import _ from 'lodash';

export default {
	name: 'PageDispensePrescription',
	components: { CardSection },
	
	data() {
		return {
			
			itemsUpdated: false,
			
			/** @type {Prescription|null} */
			prescription: null,
			
			/** @type {PrescriptionItem[]} */
			prescriptionItems: [],
		};
	},
	
	computed: {
		prescriptionId() {
			return parseInt( this.$route.params[ 'id' ] );
		},
		
		statusClass() {
			if ( this.prescription.status === 'PENDING' ) {
				return 'text-warning';
			} else {
				return 'text-success';
			}
		},
		
		isCompleted() {
			return this.prescription.status === 'COMPLETED';
		},
		
	},
	
	methods: {
		
		onUpdatePrescriptionItem: _.debounce( async function ( item ) {
			
			try {
				
				const params = {
					id: item.id,
					dose: item.dose,
					frequency: item.frequency,
					period: item.period,
					remarks: item.remarks,
					total_count: item.total_count,
				};
				
				await this.$store.dispatch( 'prescriptions/updatePrescriptionItem', params );
				
				
				this.itemsUpdated = true;
				
				setTimeout( () => {
					this.itemsUpdated = false;
				}, 3000 );
				
			} catch ( e ) {
				showErrorDialog( null, 'Invalid value provided' );
			}
			
		}, 500 ), /* onUpdatePrescriptionItem */
		
		/* basically setting the status as COMPLETED */
		async onDispense() {
			try {
				await this.$store.dispatch( 'prescriptions/updatePrescriptionStatusCompleted', this.prescriptionId );
				this.prescription = await this.$store.dispatch( 'prescriptions/fetch', this.prescriptionId );
			} catch ( e ) {
				showErrorDialog( e.response );
			}
		},
		
		async onSetAsPending() {
			try {
				await this.$store.dispatch( 'prescriptions/updatePrescriptionStatusPending', this.prescriptionId );
				this.prescription = await this.$store.dispatch( 'prescriptions/fetch', this.prescriptionId );
			} catch ( e ) {
				showErrorDialog( e.response );
			}
		},
		
		
	},
	
	async mounted() {
		try {
			
			this.prescription = await this.$store.dispatch( 'prescriptions/fetch', this.prescriptionId );
			
			if ( this.prescription ) {
				this.prescriptionItems = await this.$store.dispatch( 'prescriptions/fetchAllPrescriptionItems', this.prescriptionId );
			}
			
		} catch ( e ) {
			showErrorDialog( e.response );
		}
	},
	
};
</script>

<style scoped>

</style>
