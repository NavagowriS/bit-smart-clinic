<template>
	
	<div>
		<TopNavigationBar/>
		
		
		<div class="container" v-if="loaded">
			
			<div class="row mb-3">
				<div class="col">
					
					<div class="mb-3 text-white">
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="checkbox" id="chk_vis_chart_weight" v-model="chartsOptions.visibility.chartWeight">
							<label class="form-check-label" for="chk_vis_chart_weight">Show weight chart</label>
						</div>
						
						
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="checkbox" id="chk_vis_chart_bp" v-model="chartsOptions.visibility.chartBp">
							<label class="form-check-label" for="chk_vis_chart_bp">Show BP chart</label>
						</div>
					
					</div>
					
					
					<CardSection :no-title="true" class="mb-3" v-show="chartsOptions.visibility.chartWeight">
						<canvas height="200" width="100%" id="chart_weights"></canvas>
					</CardSection>
					
					<CardSection :no-title="true" v-show="chartsOptions.visibility.chartBp">
						<canvas height="200" width="100%" id="chart_bps"></canvas>
					</CardSection>
				
				</div>
			</div>
			
			
			<div class="row">
				<div class="col">
					
					<CardSection>
						<template v-slot:header>
							<router-link :to="{name: 'PagePatientEdit', params: {id: clinicPatient.patient_id}}" class="btn btn-sm btn-primary"><i
									class="bi bi-box-arrow-up-right"></i></router-link>
							{{ clinicPatient.patient.full_name }}'s {{ clinic.title }} Details
						</template>
						
						<div class="">
							
							<div class="mb-3">
								<button class="btn btn-primary" @click="$refs.modal_add_appointment.show()">Add new appointment</button>
							</div>
							
							<table class="table table-bordered table-striped">
								<thead>
								<tr>
									<th>Date</th>
									<th>Token No.</th>
									<th class="text-end">Weight/Height</th>
									<th class="text-end">SBP/DBP</th>
									<th class="text-end">Sugar Level</th>
									<th class="text-center">Status</th>
								</tr>
								</thead>
								<tbody>
								<tr v-for="item in clinicPatientAppointments" :key="item.id">
									<td>
										<router-link :to="_renderAppointmentLink(item)">
											{{ item.clinic_date }}
										</router-link>
									</td>
									<td>{{ item.token_number }}</td>
									<td class="text-end">{{ item.param_weight }} kg / {{ item.param_height }} m</td>
									<td class="text-end">{{ item.param_sbp }} / {{ item.param_dbp }} mmHg</td>
									<td class="text-end">{{ item.param_blood_sugar }} mg/dL</td>
									<td class="text-center">{{ item.status }}</td>
								</tr>
								</tbody>
							</table>
						
						</div>
					
					</CardSection>
				
				
				</div><!-- col -->
			</div><!-- row -->
		
		</div><!-- container -->
		
		
		<ModalWindow id="modal_aa" ref="modal_add_appointment" size="md">
			
			
			<div class="row mb-3 justify-content-center">
				<div class="col">
					
					<div class="form_add_appointment">
						<div class="mb-3">
							<label class="form-label">Clinic Date</label>
							<DateField v-model="formAddAppointment.clinic_date"/>
						</div>
						
						<div class="text-center">
							<button class="btn btn-primary" @click="onCheckAppointment()">Get token number</button>
						</div>
					
					</div>
				</div><!-- col -->
			</div><!-- row -->
			
			<div class="row">
				<div class="col">
					
					<div class="mb-3" v-if="formAddAppointment.token_number !== 0">
						
						<div class="alert alert-primary text-center">
							<p class="lead">Available token on {{ formAddAppointment.clinic_date }}</p>
							<h1>{{ formAddAppointment.token_number }}</h1>
							
							<button class="btn btn-success" @click="onBookAppointment()">Book this appointment</button>
						
						</div>
					
					</div>
					
					<div class="alert alert-success" v-if="bookingSuccessStatus">
						Appointment set!
					</div>
				
				
				</div><!-- col -->
			</div>
		
		
		</ModalWindow>
	
	
	</div><!-- template -->

</template>

<script>
import {errorDialog} from '@/assets/libs/bs-dialog.js';
import CardSection from '@/components/CardSection.vue';
import DateField from '@/components/fields/DateField.vue';
import ModalWindow from '@/components/ModalWindow.vue';
import TopNavigationBar from '@/components/TopNavigationBar.vue';
import {showErrorDialog} from '@/helpers/common.js';
import Chart from 'chart.js/auto';
import moment from 'moment';

const _ = require( 'lodash' );

export default {
	name: 'PageClinicPatientDetails',
	components: { DateField, ModalWindow, CardSection, TopNavigationBar },
	
	data() {
		return {
			
			loaded: false,
			
			formAddAppointment: {
				clinic_date: moment().format( 'YYYY-MM-DD' ),
				token_number: 0,
			},
			
			bookingSuccessStatus: false,
			
			chartsOptions: {
				visibility: {
					chartWeight: false,
					chartBp: true,
				},
			},
			
			
		};
	},
	
	computed: {
		
		/** @returns {number} */
		clinicId() {
			return parseInt( this.$route.params[ 'clinicId' ] );
		},
		
		/** @returns {number} */
		clinicPatientId() {
			return parseInt( this.$route.params[ 'clinicVisitPatientId' ] );
		},
		
		/** @return {ClinicPatient} */
		clinicPatient() {
			return this.$store.getters[ 'clinicPatients/getClinicPatient' ];
		},
		
		/** @returns {Clinic} */
		clinic() {
			return this.clinicPatient.clinic;
		},
		
		/** @returns {ClinicAppointment[]} */
		clinicPatientAppointments() {
			return this.$store.getters[ 'clinicAppointments/getClinicPatientAppointments' ];
		},
		
		weightChartData() {
			
			const data = {
				labels: [],
				datasets: [
					{
						label: 'Weight',
						borderColor: '#b36620',
						tension: 0.2,
						data: [],
					},
				],
			};
			
			const appointments = _.clone( this.clinicPatientAppointments );
			appointments.reverse();
			
			appointments.forEach( item => {
				if ( item[ 'status' ] === 'COMPLETED' ) {
					data.labels.push( item[ 'clinic_date' ] );
					data.datasets[ 0 ].data.push( item[ 'param_weight' ] );
				}
			} );
			return data;
		},
		
		bpChartData() {
			const data = {
				labels: [],
				datasets: [
					{
						label: 'SBP',
						backgroundColor: '#198754',
						tension: 0.2,
						data: [],
					},
					{
						label: 'DBP',
						backgroundColor: '#3b5873',
						tension: 0.2,
						data: [],
					},
				],
			};
			
			const appointments = _.clone( this.clinicPatientAppointments );
			appointments.reverse();
			
			appointments.forEach( item => {
				if ( item[ 'status' ] === 'COMPLETED' ) {
					data.labels.push( item[ 'clinic_date' ] );
					data.datasets[ 0 ].data.push( item[ 'param_sbp' ] );
					data.datasets[ 1 ].data.push( item[ 'param_dbp' ] );
				}
			} );
			return data;
		},
		
		
	},
	
	
	async mounted() {
		
		try {
			
			await this.$store.dispatch( 'clinicPatients/fetch', this.clinicPatientId );
			await this.$store.dispatch( 'clinicAppointments/fetchByClinicPatient', this.clinicPatientId );
			
			this.loaded = true;
			
		} catch ( e ) {
			showErrorDialog( e.response, 'Failed to fetch patient details' );
		}
		
		
		this.$nextTick( () => {
			
			
			/* chart chart_appointments_stats */
			const chartWeights = new Chart( 'chart_weights', {
				type: 'line',
				data: this.weightChartData,
				options: {
					maintainAspectRatio: false,
				},
			} );
			
			const chartBPs = new Chart( 'chart_bps', {
				type: 'bar',
				data: this.bpChartData,
				options: {
					maintainAspectRatio: false,
				},
			} );
			
			chartWeights.canvas.parentNode.style.height = '200px';
			
			
		} );
		
	},
	
	
	methods: {
		
		async onCheckAppointment() {
			
			try {
				
				const params = {
					clinic_id: this.clinicId,
					clinic_date: this.formAddAppointment.clinic_date,
					clinic_patient_id: this.clinicPatientId,
				};
				
				this.formAddAppointment.token_number = await this.$store.dispatch( 'publicPatient/checkAppointmentToken', params );
				
			} catch ( e ) {
				console.log( e );
			}
			
		},
		
		async onBookAppointment() {
			
			this.bookingSuccessStatus = false;
			
			try {
				
				const params = {
					clinic_id: this.clinicId,
					clinic_date: this.formAddAppointment.clinic_date,
					clinic_patient_id: this.clinicPatientId,
					token_number: this.formAddAppointment.token_number,
				};
				
				await this.$store.dispatch( 'publicPatient/bookAppointment', params );
				
				this.bookingSuccessStatus = true;
				
				await this.$store.dispatch( 'clinicAppointments/fetchByClinicPatient', this.clinicPatientId );
				
			} catch ( e ) {
				errorDialog( {
					message: 'Failed to book appointment',
				} );
			}
		},
		
		
		/**
		 * Render clinic visit detail page url for individual patient
		 *
		 * @param appointment
		 */
		_renderAppointmentLink( appointment ) {
			return {
				name: 'pageSingleAppointment',
				params: {
					clinicId: this.clinicId,
					appointmentId: appointment.id,
				},
			};
		},
		
	},
	
	
};
</script>

<style scoped>

</style>
