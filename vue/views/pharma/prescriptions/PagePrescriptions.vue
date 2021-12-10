<template>
	
	<div>
		
		<RecentPrescriptions :date-range="dateRange" status="COMPLETED" :simple="false"/>
	
	</div>

</template>

<script>
import RecentPrescriptions from '@/views/pharma/dispense/RecentPrescriptions.vue';
import moment from 'moment';

export default {
	name: 'PagePrescriptions',
	components: { RecentPrescriptions },
	
	data() {
		return {
			dateRange: {
				startDate: moment().subtract( 1, 'months' ).format( 'YYYY-MM-DD' ),
				endDate: moment().format( 'YYYY-MM-DD' ),
			},
		};
	},
	
};
</script>

<style scoped>

</style>
