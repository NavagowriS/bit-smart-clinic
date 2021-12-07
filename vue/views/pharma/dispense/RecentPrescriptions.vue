<template>
	
	<div>
		
		<CardSection>
			<template v-slot:header>Recent Prescriptions</template>
			
			<div class="mb-3 d-flex gap-3 justify-content-end">
				<div class="">
					<DateRangeField v-model="dateRange"/>
				</div>
				<div class="">
					<select class="form-select" v-model="status">
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
						<td>{{ item.id }}</td>
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

export default {
	name: 'RecentPrescriptions',
	components: { DateRangeField, CardSection },
	
	data() {
		return {
			
			/** @type {Prescription[]} */
			latestPendingPrescriptions: [],
			
			dateRange: {
				startDate: moment().subtract( 1, 'months' ).format( 'YYYY-MM-DD' ),
				endDate: moment().format( 'YYYY-MM-DD' ),
			},
			
			status: 'PENDING',
			
			allStatuses: {
				'PENDING': 'Pending',
				'COMPLETED': 'Completed',
			},
			
		};
	},
	
	computed: {
		//
	},
	
	methods: {
		async onSearch() {
			try {
				
				const params = {
					start_date: this.dateRange.startDate,
					end_date: this.dateRange.endDate,
					status: this.status,
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
				start_date: this.dateRange.startDate,
				end_date: this.dateRange.endDate,
				status: this.status,
			};
			
			this.latestPendingPrescriptions = await this.$store.dispatch( 'pharmacyDrugs/fetchPrescriptions', params );
			
		} catch ( e ) {
			showErrorDialog( e.response );
		}
	},
	
};
</script>

<style scoped>

</style>
