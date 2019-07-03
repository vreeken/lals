<template>
	<div>
		<canvas :id="'src'+question.id"></canvas>
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

				labels: ['Yes', 'No'],

				answerCounts: [],
			}
		},
		mounted() {
			let y = 0;
			let n = 0;
			for (let i=0; i<this.answers.length; i++) {
				if (this.answers[i].answer === "yes") {
					y++;
				}
				else {
					n++;
				}
			}
			this.answerCounts.push(y);
			this.answerCounts.push(n);

			if (this.question.question_type === 4) {
				this.drawChart();
			}
		},
		methods: {
			drawChart: function() {
				const ctx = document.getElementById('src'+this.question.id).getContext("2d");


				var gradient = ctx.createLinearGradient(0, 0, 0, 400);
				gradient.addColorStop(0, 'rgba(250,174,50,1)');
				gradient.addColorStop(1, 'rgba(250,174,50,0)');

				const labels = this.labels;
				const answerCounts = this.answerCounts;

				const chartData = {
					labels: this.labels,
					datasets: [
						{
							label: null,
							data: answerCounts,
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
