<template>
	<div>
		<div v-if="invoice.paid_at === null">
			Invoice: {{ invoice.invoice_number }} | Due Date: {{ invoice.due_date | niceDate }} | Amount Due: ${{ (invoice.amount_due / 100.0).toFixed(2) }} | <a :href="invoice.invoice_pdf" target="_blank">View Invoice</a> | Status: Unpaid | <button class="btn btn-primary" :disabled="payInvoiceBusy" @click="onPayInvoiceClick()">Pay Invoice</button>
		</div>
		<div v-else>
			Invoice: {{ invoice.invoice_number }} | Due Date: {{ invoice.due_date | niceDate }} | Amount Paid: ${{ (invoice.amount_paid / 100.0).toFixed(2) }} | <a :href="invoice.invoice_pdf" target="_blank">View Invoice</a> | Status: Paid on {{ invoice.paid_at | niceDate }} | <a :href="invoice.receipt_url" target="_blank">View Receipt</a>
		</div>
	</div>
</template>

<script>
	
	export default {
		props: ['invoice'],
		data() {
			return {
				payInvoiceBusy: false,
			}
		},
		mounted() {

		},
		methods: {
			onPayInvoiceClick() {
				this.payInvoiceBusy=true;

				axios.post("/members/invoices/request-email", {'invoice_number': this.invoice.invoice_number})
					.then(response => {
						if (response.data && response.data.success) {
							this.$noty.success("Check your email to pay your invoice.");
						}
						else {
							this.$noty.error("Oops, something went wrong! Please try again.");
						}
					})
					.catch(error => {
						this.$noty.error("Oops, something went wrong! Please try again.");
					}).then(() => {
						this.payInvoiceBusy=false;
				});
			}
		},
		filters: {
			niceDate: function(date) {
				const d = new Date(date.replace(/-/g,"/"));
				return d.toLocaleDateString("en-US");
			}
		}
	}
</script>
