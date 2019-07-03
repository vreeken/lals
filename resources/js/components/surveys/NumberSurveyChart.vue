<template>
	<div>
		<div>
			<canvas :id="'src'+question.id"></canvas>
		</div>
		<div>
			<div v-if="totalAverage">
				Note: outliers have been removed in the chart.
				<br />
				Average: {{normalizedAverage}} (absolute average before outliers were removed: {{totalAverage}})
				<br />
			</div>
			<div v-else>
				Average: {{normalizedAverage}}
			</div>
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
								beginAtZero : true,
								//stepSize: 1,
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
								labelString: "Number Range"
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
			let quartileRange;

			if (this.answers.length <= 10 || parseInt(this.answers[len-1].answer) - parseInt(this.answers[0].answer) <= 10) {
				//Calculate standard deviation via median, first and last quartile and remove outliers
				//this.median = Math.round(this.answers[parseInt(len * .5)].answer);
				//const firstQuartile = Math.round(this.answers[parseInt(len * .25)].answer);
				//const lastQuartile = Math.round(this.answers[parseInt(len * .75)].answer);

				this.upperLimit = parseInt(this.answers[len-1].answer); //Math.round((this.median + quartileRange).toFixed(2));
				this.lowerLimit = parseInt(this.answers[0].answer); //Math.round((this.median - quartileRange).toFixed(2));
				quartileRange = this.upperLimit - this.lowerLimit;

				//Calculate steps to divide data into 10 aggregated groups of data,
				//add data count into appropriate step range
				//We also calculate the averages of both all data and standardized data
				const stepAmountsMap = {};
				for (let i = 0; i < len; i++) {
					const r = this.answers[i].answer;
					if (stepAmountsMap[r] === undefined) {
						stepAmountsMap[r] = {
							label: r,
							count: 1
						}
					}
					else {
						stepAmountsMap[r].count++;
					}
					this.normalizedAverage += parseInt(r);
				}
				Object.keys(stepAmountsMap).forEach((key) => {
					this.stepAmounts.push(stepAmountsMap[key].label);
					this.stepCounts.push(stepAmountsMap[key].count);
				});

				this.totalAverage = null;//Math.round((this.totalAverage / len));
				this.normalizedAverage = parseFloat((this.normalizedAverage / len).toFixed(2));
			}
			else {
				//Sort the answers in ascending order, so we can find medians
				this.answers = this.answers.sort(function(a, b) {
					if (parseInt(a.answer) > parseInt(b.answer)) {
						return 1;
					}
					else {
						return -1;
					}
				});

				//Calculate standard deviation via median, first and last quartile and remove outliers
				this.median = Math.round(this.answers[parseInt(len * .5)].answer);
				const firstQuartile = Math.round(this.answers[parseInt(len * .25)].answer);
				const lastQuartile = Math.round(this.answers[parseInt(len * .75)].answer);
				quartileRange = Math.round(lastQuartile - firstQuartile);
				this.upperLimit = lastQuartile; //Math.round((this.median + quartileRange).toFixed(2));
				this.lowerLimit = firstQuartile; //Math.round((this.median - quartileRange).toFixed(2));

				//Calculate steps to divide data into 10 aggregated groups of data,
				//add data count into appropriate step range
				//We also calculate the averages of both all data and standardized data
				for (let i = 0; i < this.steps; i++) {
					const r = Math.round(((quartileRange / this.steps) * i) + this.lowerLimit);
					if (this.stepAmounts.length) {
						if (this.stepAmounts[this.stepAmounts.length - 1] === r) {
							continue;
						}
					}
					this.stepAmounts.push(r);
					this.stepCounts.push(0);
				}

				for (let i = 0; i < len; i++) {
					const a = Math.round(this.answers[i].answer);
					this.totalAverage += a;
					if (a >= this.lowerLimit && a <= this.upperLimit) {
						this.normalData.push(a);
						this.normalizedAverage += a;

						for (let j = this.steps - 1; j >= 0; j--) {
							if (a >= this.stepAmounts[j]) {
								this.stepCounts[j]++;
								break;
							}
						}
					} else {
						this.outlierData.push(a);
					}
				}
				this.totalAverage = Math.round((this.totalAverage / len));
				this.normalizedAverage = Math.round(this.normalizedAverage / this.normalData.length);
			}

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
