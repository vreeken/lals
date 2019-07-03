<template>
	<div>
		<div>
			<canvas :id="'src'+question.id"></canvas>
		</div>
		<div>
			Note: outliers have been removed in the chart.
			<br />
			Average: {{normalizedAverage}} (absolute average before outliers were removed: {{totalAverage}})
			<br />
		</div>
	</div>
</template>
<script>
	const BACKGROUND_COLORS = ['rgba(253, 220, 115, 0.5)','rgba(232, 242, 181, 0.5)','rgba(253, 219, 220, 0.5)','rgba(144, 184, 233, 0.5)','rgba(253, 156, 103, 0.5)','rgba(250, 238, 178, 0.5)','rgba(172, 236, 210, 0.5)','rgba(197, 197, 233, 0.5)','rgba(172, 228, 243, 0.5)','rgba(253, 197, 172, 0.5)'];
	const BORDER_COLORS = ['rgba(253, 220, 115, 1)','rgba(232, 242, 181, 1)','rgba(253, 219, 220, 1)','rgba(144, 184, 233, 1)','rgba(253, 156, 103, 1)','rgba(250, 238, 178, 1)','rgba(172, 236, 210, 1)','rgba(197, 197, 233, 1)','rgba(172, 228, 243, 1)','rgba(253, 197, 172, 1)'];

	export default {
		props: ['question', 'answers'],
		components: {

		},
		data() {
			return {
				options: {
					responsive: true,
					maintainAspectRatio: false,
					legend: {
						display: false,
					},
					scales : {
						yAxes : [{
							ticks : {
								beginAtZero : true
							},
							scaleLabel: {
								display: true,
								labelString: "# in Range"
							}
						}],

						xAxes: [{
							ticks: {
								//beginAtZero: true
							},
							scaleLabel: {
								display: true,
								labelString: "Decimal Range"
							},
						}]

					}
				},

				normalData: [],
				outlierData: [],
				totalAverage: 0,
				normalizedAverage: 0,
				median: 0,
				upperLimit: 0,
				lowerLimit: 0,

				steps: 10,
				stepAmounts: [],
				stepCounts: []
			}
		},
		mounted() {
			const len = this.answers.length;

			//Sort the array of answers in ascending order
			this.answers = this.answers.sort(function(a, b) {
				if (parseFloat(a.answer) > parseFloat(b.answer)) {
					return 1;
				}
				else {
					return -1;
				}
			});

			//Calculate standard deviation via median, first and last quartile and remove outliers

			this.median = parseFloat(this.answers[parseInt(len*.5)].answer);
			const firstQuartile = parseFloat(this.answers[parseInt(len*.25)].answer);
			const lastQuartile = parseFloat(this.answers[parseInt(len*.75)].answer);
			const quartileRange = parseFloat(lastQuartile - firstQuartile);
			this.upperLimit = lastQuartile; //parseFloat((this.median + quartileRange).toFixed(2));
			this.lowerLimit = firstQuartile; //parseFloat((this.median - quartileRange).toFixed(2));

			//Calculate steps to divide data into 10 aggregated groups of data,
			//add data count into appropriate step range
			//We also calculate the averages of both all data and standardized data

			for (let i=0; i < this.steps; i++) {
				this.stepAmounts.push( parseFloat( ((quartileRange / this.steps) * i ) + this.lowerLimit ).toFixed(2));
				this.stepCounts.push(0);
			}
			for (let i=0; i<len; i++) {
				const a = parseFloat(this.answers[i].answer);
				this.totalAverage += a;
				if (a >= this.lowerLimit && a <= this.upperLimit) {
					this.normalData.push(a);
					this.normalizedAverage += a;
					
					for (let j=this.steps-1; j>=0; j--) {
						if (a >= this.stepAmounts[j]) {
							this.stepCounts[j]++;
							break;
						}
					}
				}
				else {
					this.outlierData.push(a);
				}
			}

			this.totalAverage = parseFloat((this.totalAverage / len).toFixed(2));
			this.normalizedAverage = parseFloat((this.normalizedAverage / this.normalData.length).toFixed(2));
						
			this.drawChart();
		},
		methods: {
			drawChart: function() {
				const ctx = document.getElementById('src'+this.question.id).getContext("2d");

				const chartData = {
					labels: this.stepAmounts,
					datasets: [
						{
							label: null,
							data: this.stepCounts,
							backgroundColor: BACKGROUND_COLORS,
							borderColor: BORDER_COLORS,
							borderWidth: 1,
						},

					]
				};

				const myChart = new Chart(ctx, {
					data: chartData,
					options: this.options,
					type: 'bar',
				});

			},
		}
	}
</script>
