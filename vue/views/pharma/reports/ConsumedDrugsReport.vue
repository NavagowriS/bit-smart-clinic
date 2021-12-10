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
			
			<div class="">
				<button class="btn btn-primary" @click="onAddData">Add Data</button>
			</div>
		
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
				startDate: moment().subtract( 7, 'days' ).format( 'YYYY-MM-DD' ),
				endDate: moment().format( 'YYYY-MM-DD' ),
			},
			
			selectedClinicId: 0,
			
			
			/* chart data */
			
			barChart: {
				
				options: {
					chart: {
						id: 'apex-bar-chart',
					},
					plotOptions: {
						bar: {
							horizontal: true,
							borderRadius: 5,
						},
					},
					xaxis: {
						categories: [1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998],
					},
				},
				
				series: [{
					name: 'series 1',
					data: [30, 40, 45, 50, 49, 60, 70, 91],
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
		
		},
		
		onAddData() {
			this.barChart.options.xaxis.categories.push( 1999 );
			this.barChart.series = [{
				data: [30, 40, 45, 50, 49, 60, 70, 91, 120],
			}];
		},
		
	},
	
	async mounted() {
		try {
			
			await this.$store.dispatch( 'clinics/fetchAll' );
			
		} catch ( e ) {
			showErrorDialog( e.response );
		}
	},
	
};
</script>

<style scoped>

</style>
