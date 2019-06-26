<template>
	<div>
		<div v-for="(invoice, index) in invoices">
			<invoice :invoice="invoice" :key="index"></invoice>
		</div>
	</div>
</template>

<script>

	export default {
		props: ['invoices'],
		data() {
			return {

			}
		},
		mounted() {

		},
		methods: {

		}
	}
</script>
