<template>
	
	<div>
		
		<div class="row mb-3" v-if="!prescription">
			<div class="col">
				<button class="btn btn-primary" @click="onCreatePrescription()">Add a prescription</button>
			</div><!-- col -->
		</div><!-- row -->
		
		<div class="row mb-3" v-if="prescription">
			<div class="col">
				<CardSection>
					<template v-slot:header>Prescription</template>
					
					<div class="">
						
						<div class="row mb-3">
							<div class="col">
								<button class="btn btn-primary" @click="$refs.modal_search_drugs.show()">Add Drugs</button>
							</div>
						</div>
						
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
								<th style="width: 120px">Frequency (Per Day)</th>
								<th style="width: 120px">Period (Days)</th>
								<th>Remarks</th>
								<th style="width: 10px"></th>
							</tr>
							</thead>
							<tbody>
							<tr v-for="item in prescriptionItems">
								<td>{{ item.drug.drug_name }}</td>
								<td>
									<input type="number"
												 class="form-control form-control-sm text-end"
												 min="1"
												 v-model.number="item['dose']"
												 @change="onUpdatePrescriptionItem(item)">
								</td>
								<td>
									<input type="text"
												 class="form-control form-control-sm text-end"
												 min="1"
												 v-model="item['frequency']"
												 @change="onUpdatePrescriptionItem(item)">
								</td>
								<td>
									<input type="number"
												 class="form-control form-control-sm text-end"
												 min="1"
												 v-model.number="item['period']"
												 @change="onUpdatePrescriptionItem(item)">
								</td>
								<td>
									<input type="text"
												 class="form-control form-control-sm"
												 v-model="item['remarks']"
												 @change="onUpdatePrescriptionItem(item)">
								</td>
								<td>
									<button class="btn btn-sm btn-danger" @click="onDeletePrescriptionItem(item)">
										<i class="bi bi-trash-fill"></i>
									</button>
								</td>
							</tr>
							</tbody>
						</table>
						
						<div class="text-center my-3" v-if="itemsUpdated">
              <span class="alert alert-success p-1">
                ✅ Item details updated
              </span>
						</div>
					
					</div>
				
				</CardSection>
			</div>
		</div>
		
		
		<!-- MODAL: Search & add drugs -->
		<ModalWindow id="mdl_sad" ref="modal_search_drugs" title="Search & Add Drugs" size="lg">
			
			<div class="mb-3">
				<input type="text"
							 class="form-control"
							 placeholder="Search for drugs"
							 @keydown="onSearchDrugs"
							 v-model="drugsSearch.keyword">
			</div>
			
			<table class="table table-bordered table-sm">
				<thead>
				<tr>
					<th></th>
					<th>Drug</th>
					<th>Generic Name</th>
					<th>Brand Name</th>
				</tr>
				</thead>
				<tbody>
				<tr v-for="item in drugsList">
					<td>
						<button class="btn btn-primary btn-sm" @click="onAddDrug(item)">Add</button>
					</td>
					<td>{{ item.drug_name }}</td>
					<td>{{ item.generic_name }}</td>
					<td>{{ item.brand_name }}</td>
				</tr>
				</tbody>
			</table>
		
		</ModalWindow>
	
	</div>

</template>

<script>
import CardSection from '@/components/CardSection.vue';
import ModalWindow from '@/components/ModalWindow.vue';
import {showErrorDialog} from '@/helpers/common.js';

import _ from 'lodash';

export default {
	name: 'AddPrescription',
	components: { ModalWindow, CardSection },
	
	props: {
		appointment: {
			required: true,
			type: Object,
		},
	},
	
	data() {
		return {
			
			prescription: null,
			prescriptionItems: [],
			itemsUpdated: false,
			
			
			drugsList: [],
			
			drugsSearch: {
				searched: false,
				keyword: '',
			},
			
			
		};
	},
	
	computed: {
		appointmentId() {
			return parseInt( this.$route.params[ 'appointmentId' ] );
		},
	},
	
	methods: {
		
		async onCreatePrescription() {
			try {
				
				const params = {
					appointment_id: this.appointment.id,
					prescription_date: this.appointment.clinic_date,
					remarks: '',
				};
				
				await this.$store.dispatch( 'prescriptions/createPrescription', params );
				
				this.prescription = await this.$store.dispatch( 'prescriptions/fetchByAppointment', this.appointmentId );
				
			} catch ( e ) {
				showErrorDialog( e.response, 'Failed to create a prescription' );
			}
		}, /* onCreatePrescription */
		
		onSearchDrugs: _.debounce( async function () {
			
			if ( this.drugsSearch.keyword === '' ) return;
			
			try {
				this.drugsList = await this.$store.dispatch( 'pharmacyDrugs/search', this.drugsSearch.keyword );
				this.drugsSearch.searched = true;
				
			} catch ( e ) {
				
				showErrorDialog( e.response, 'Failed to fetch search results' );
				
			}
			
		}, 500 ), /* onSearchDrugs */
		
		async onAddDrug( drug ) {
			try {
				
				const params = {
					prescription_id: this.prescription.id,
					drug_id: drug.id,
				};
				
				await this.$store.dispatch( 'prescriptions/addPrescriptionItem', params );
				
				this.prescriptionItems = await this.$store.dispatch( 'prescriptions/fetchAllPrescriptionItems', this.prescription.id );
				
			} catch ( e ) {
				showErrorDialog( e.response );
			}
		}, /* onAddDrug */
		
		onUpdatePrescriptionItem: _.debounce( async function ( item ) {
			
			try {
				
				const params = {
					id: item.id,
					dose: item.dose,
					frequency: item.frequency,
					period: item.period,
					remarks: item.remarks,
				};
				
				await this.$store.dispatch( 'prescriptions/updatePrescriptionItem', params );
				
				
				this.itemsUpdated = true;
				
				setTimeout( () => {
					this.itemsUpdated = false;
				}, 3000 );
				
			} catch ( e ) {
				showErrorDialog( null, 'Invalid value provided' );
			}
			
		}, 500 ), /* on update prescription item */
		
		
		async onDeletePrescriptionItem( item ) {
			try {
				await this.$store.dispatch( 'prescriptions/deletePrescriptionItem', item.id );
				
				this.prescriptionItems = await this.$store.dispatch( 'prescriptions/fetchAllPrescriptionItems', this.prescription.id );
				
				this.itemsUpdated = true;
				setTimeout( () => {
					this.itemsUpdated = false;
				}, 1000 );
				
			} catch ( e ) {
				showErrorDialog( e.response );
			}
		},
		
	},
	
	async mounted() {
		try {
			
			this.prescription = await this.$store.dispatch( 'prescriptions/fetchByAppointment', this.appointmentId );
			
			if ( this.prescription ) {
				this.prescriptionItems = await this.$store.dispatch( 'prescriptions/fetchAllPrescriptionItems', this.prescription.id );
			}
			
		} catch ( e ) {
			showErrorDialog( e.response );
		}
		
	},
	
};
</script>

<style scoped>

</style>
