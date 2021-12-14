<template>
	
	<CardSection class="mb-3">
		<template v-slot:header>Most consumed drugs</template>
		
		<!-- control panel -->
		<div class="mb-3 d-flex gap-3 justify-content-end">
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
				<button class="btn btn-primary" @click="onSearch()">Search</button>
			</div>
		</div>
		<!-- control panel -->
		
		<div>
			
			<apex-chart height="300" type="bar" :options="barChart.options" :series="barChart.series"/>
		</div>
	
	</CardSection>

</template>

<script>
import CardSection from '@/components/CardSection.vue';
import DateRangeField from '@/components/fields/DateRangeField.vue';
import {showErrorDialog} from '@/helpers/common.js';
import moment from 'moment';

export default {
	name: 'ConsumedDrugsReport',
	components: { DateRangeField, CardSection },
	
	data() {
		return {
			
			dateRangeValue: {
				startDate: moment().subtract( 1, 'months' ).format( 'YYYY-MM-DD' ),
				endDate: moment().format( 'YYYY-MM-DD' ),
			},
			
			selectedClinicId: 0,
			
			dispensedDrugsStats: null,
			
			/* chart data */
			
			barChart: {
				
				options: {
					chart: {
						id: 'apex-bar-chart',
					},
					plotOptions: {
						bar: {
							horizontal: true,
							borderRadius: 2,
						},
					},
					xaxis: {
						categories: [],
					},
				},
				
				series: [{
					name: 'Dispensed',
					data: [],
				}],
				
			},
			
			
		};
	},
	
	computed: {
		/** @return {Clinic[]} */
		clinicsList() {
			return this.$store.getters[ 'clinics/getClinicsList' ];
		},
	},
	
	methods: {
		
		async onSearch() {
			await this._fetchStats();
		},
		
		
		async _fetchStats() {
			try {
				
				const params = {
					start_date: this.dateRangeValue.startDate,
					end_date: this.dateRangeValue.endDate,
					clinic_id: this.selectedClinicId,
				};
				
				this.dispensedDrugsStats = await this.$store.dispatch( 'pharmacyStats/statsDispensedCounts', params );
				
				this._updateChart();
				
			} catch ( e ) {
				showErrorDialog( e.response );
			}
		},
		
		_updateChart() {
			this.barChart.series = [{
				data: this.dispensedDrugsStats[ 'chart_data' ][ 'values' ],
			}];
			
			this.barChart.options = {
				xaxis: {
					categories: this.dispensedDrugsStats[ 'chart_data' ][ 'labels' ],
				},
			};
		},
		
	},
	
	async mounted() {
		try {
			
			await this.$store.dispatch( 'clinics/fetchAll' );
			await this._fetchStats();
			
		} catch ( e ) {
			showErrorDialog( e.response );
		}
	},
	
};
</script>

<style scoped>

</style>
