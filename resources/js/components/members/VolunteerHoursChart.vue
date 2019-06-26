<script>

	import { Line } from 'vue-chartjs'

	export default {
		extends: Line,
		props: ['hours'],
		components: {

		},
		data() {
			return {
				options: {
					responsive: true,
					maintainAspectRatio: false,
					scales : {
						yAxes : [{
							ticks : {
								beginAtZero : true
							}
						}],
						xAxes: [{
							type: 'time',
							time: {
								displayFormats: {
									quarter: 'YYYY-MM-DD'
								}
							}
						}]
					}
				}
			}
		},
		mounted() {
			const labels = [], singleHoursData = [], totalHoursData = [];
			let total = 0;

			for (let i=0; i<this.hours.length; i++) {
				if (i===0) {
					//Check if there is an entry for January first, if not create an entry with 0 hours
					if (this.hours[i].date_worked.substr(5, 5) !== '01-01') {
						labels.push(this.hours[i].date_worked.substr(0, 4) + "-01-01");
						singleHoursData.push(0);
						totalHoursData.push(0);
					}
				}
				let d = new Date(this.hours[i].date_worked);
				total += this.hours[i].hours;

				labels.push(d);

				singleHoursData.push(this.hours[i].hours)
				totalHoursData.push(total);
			}
			const chartData = {
				labels: labels,
				datasets: [{
					label: 'Total Hours',
					data: totalHoursData,
					backgroundColor: [
						'rgba(255, 99, 132, 0.2)'
					],
					borderColor: [
						'rgba(255, 99, 132, 1)'
					],
					borderWidth: 1
				},
					{
						label: 'Input Hours',
						data: singleHoursData,
						backgroundColor: [
							'rgba(54, 162, 235, 0.2)'
						],
						borderColor: [
							'rgba(54, 162, 235, 1)'
						],
						borderWidth: 1
					}]
			};

			this.renderChart(chartData, this.options);
		},
		methods: {

		},
		filters: {

		}
	}
</script>
