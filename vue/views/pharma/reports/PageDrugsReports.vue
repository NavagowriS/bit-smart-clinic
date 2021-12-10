<template>
	
	<div>
		
		<ConsumedDrugsReport/>
		
	</div>

</template>

<script>
import CardSection from '@/components/CardSection.vue';
import ConsumedDrugsReport from '@/views/pharma/reports/ConsumedDrugsReport.vue';

export default {
	name: 'PageDrugsReports',
	components: { ConsumedDrugsReport, CardSection },
};
</script>

<style scoped>

</style>
