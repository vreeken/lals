<template>
	<div>
		<canvas :id="'src'+question.id" :height="Math.max(counts.length * 40, 150)"></canvas>
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
						/*yAxes : [{
							ticks : {
								beginAtZero : true
							},
							scaleLabel: {
								display: true,
								labelString: "Hours"
							}
						}],*/

						xAxes: [{
							ticks: {
								beginAtZero: true
							},
							barPercentage: 0.5,
							barThickness: 6,
							maxBarThickness: 8,
							minBarLength: 2
						}]

					}
				},

				//labels: ['Yes', 'No'],
				labels: [],
				counts: [],
			}
		},
		mounted() {
			const len = this.answers.length;

			const stepAmountsMap = {};
			for (let i = 0; i < len; i++) {
				const r = JSON.parse(this.answers[i].answer);
				const len2 = r.length;
				for (let j=0; j<len2; j++) {
					if (stepAmountsMap[r[j]] === undefined) {
						stepAmountsMap[r[j]] = {
							label: r[j],
							count: 1
						}
					} else {
						stepAmountsMap[r[j]].count++;
					}
				}
			}
			const choices = JSON.parse(this.question.choices);
			Object.keys(stepAmountsMap).forEach((key) => {
				this.labels.push(choices[stepAmountsMap[key].label]);
				this.counts.push(stepAmountsMap[key].count);
			});

			//Make sure we set the dom canvas height via the counts length before actually drawing the chart
			this.$nextTick(() => {
				this.drawChart();
			});

		},
		methods: {
			drawChart: function() {
				const ctx = document.getElementById('src'+this.question.id).getContext("2d");

				//const labels = this.labels;
				//const answerCounts = this.answerCounts;

				const chartData = {
					labels: this.labels,
					datasets: [
						{
							label: null,
							data: this.counts,
							backgroundColor: BACKGROUND_COLORS,
							borderColor: BORDER_COLORS,
							borderWidth: 1,
						},

					]
				};

				const myChart = new Chart(ctx, {
					data: chartData,
					options: this.options,
					type: 'horizontalBar',
				});

			},
		}
	}
</script>
