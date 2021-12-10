<template>
	
	<div>
		
		<CardSection>
			<template v-slot:header>{{ statusString }} prescriptions: {{ dateRangeValue.startDate }} &mdash; {{ dateRangeValue.endDate }}</template>
			
			<div class="mb-3 d-flex gap-3 justify-content-end" v-if="!simple">
				<div class="">
					<DateRangeField v-model="dateRangeValue"/>
				</div>
				<div class="">
					<select class="form-select" v-model="selectedClinicId">
						<option value="0">All Clinics</option>
						<option v-for="item in clinicsList" :value="item.id">{{ item.title }}</option>
					</select>
				</div>
				<div class="">
					<select class="form-select" v-model="statusValue">
						<option v-for="(item, key) in allStatuses" :value="key">{{ item }}</option>
					</select>
				</div>
				<div class="">
					<button class="btn btn-primary" @click="onSearch()">Search</button>
				</div>
			</div>
			
			<div>
				<table class="table table-bordered table-sm" v-if="latestPendingPrescriptions.length > 0">
					<thead>
					<tr>
						<th>ID</th>
						<th>Date</th>
						<th>Clinic</th>
						<th>Patient</th>
						<th>Token Number</th>
					</tr>
					</thead>
					<tbody>
					<tr v-for="item in latestPendingPrescriptions">
						<td>
							<router-link :to="{name:'PageDispensePrescription', params: {id: item.id}}">{{ item.id }}</router-link>
						</td>
						<td>{{ item.prescription_date }}</td>
						<td>{{ item.appointment.clinic.title }}</td>
						<td>{{ item.appointment.clinic_patient.patient.full_name }}</td>
						<td>{{ item.appointment.token_number }}</td>
					</tr>
					</tbody>
				</table>
				
				<div v-else>
					No prescriptions found.
				</div>
			
			</div>
		
		</CardSection>
	
	
	</div>

</template>

<script>
import CardSection from '@/components/CardSection.vue';
import DateRangeField from '@/components/fields/DateRangeField.vue';
import {showErrorDialog} from '@/helpers/common.js';
import moment from 'moment';
import voca from 'voca';

export default {
	name: 'RecentPrescriptions',
	components: { DateRangeField, CardSection },
	
	props: {
		dateRange: {
			type: Object,
			default: function () {
				return {
					startDate: moment().subtract( 7, 'days' ).format( 'YYYY-MM-DD' ),
					endDate: moment().format( 'YYYY-MM-DD' ),
				};
			},
		},
		status: {
			type: String,
			default: 'PENDING',
		},
		simple: {
			type: Boolean,
			default: true,
		},
	},
	
	data() {
		return {
			
			/** @type {Prescription[]} */
			latestPendingPrescriptions: [],
			
			allStatuses: {
				'PENDING': 'Pending',
				'COMPLETED': 'Completed',
			},
			
			dateRangeValue: this.dateRange,
			statusValue: this.status,
			
			selectedClinicId: 0,
			
		};
	},
	
	computed: {
		
		/** @return {Clinic[]} */
		clinicsList() {
			return this.$store.getters[ 'clinics/getClinicsList' ];
		},
		
		statusString() {
			return voca.titleCase( this.statusValue );
		},
		
	},
	
	methods: {
		async onSearch() {
			try {
				
				const params = {
					start_date: this.dateRangeValue.startDate,
					end_date: this.dateRangeValue.endDate,
					status: this.statusValue,
					clinic_id: this.selectedClinicId,
				};
				
				this.latestPendingPrescriptions = await this.$store.dispatch( 'pharmacyDrugs/fetchPrescriptions', params );
				
			} catch ( e ) {
				showErrorDialog( e.response );
			}
		}, /* on search */
	},
	
	async mounted() {
		try {
			
			const params = {
				start_date: this.dateRangeValue.startDate,
				end_date: this.dateRangeValue.endDate,
				status: this.statusValue,
				clinic_id: this.selectedClinicId,
			};
			
			this.latestPendingPrescriptions = await this.$store.dispatch( 'pharmacyDrugs/fetchPrescriptions', params );
			
			await this.$store.dispatch( 'clinics/fetchAll' );
			
		} catch ( e ) {
			showErrorDialog( e.response );
		}
	},
	
};
</script>

<style scoped>

</style>
