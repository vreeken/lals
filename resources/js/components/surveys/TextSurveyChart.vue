<template>
	<div>
		<canvas :id="'src'+question.id" :height="Math.max(wordsToShow * 40, 150)"></canvas>
	</div>
</template>
<script>
	const STOPWORDS = ["a","able","about","across","after","all","almost","also","am","among","an","and","any","are","as","at","be","because","been","but","by","can","cannot","could","dear","did","do","does","either","else","ever","every","for","from","get","got","had","has","have","he","her","hers","him","his","how","however","i","if","in","into","is","it","its","just","least","let","like","likely","may","me","might","most","must","my","neither","no","nor","not","of","off","often","on","only","or","other","our","own","rather","said","say","says","she","should","since","so","some","than","that","the","their","them","then","there","these","they","this","tis","to","too","twas","us","wants","was","we","were","what","when","where","which","while","who","whom","why","will","with","would","yet","you","your"];
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

				wordsToShow: 10,
				topWords: [],
				topWordCounts: [],
			}
		},
		mounted() {
			const len = this.answers.length;

			//Create a map of words with corresponding counts
			const wordMap = {};
			for (let i = 0; i < len; i++) {
				const r = this.answers[i].answer.toLowerCase().split(' ');
				const len2 = r.length;
				for (let j=0; j<len2; j++) {
					if (wordMap[r[j]] === undefined) {
						wordMap[r[j]] = {
							word: r[j],
							count: 1
						}
					}
					else {
						wordMap[r[j]].count++;
					}
				}

			}

			//Convert wordMap to an array, filtering out common stopwords
			const wordsArray = [];
			Object.keys(wordMap).forEach((key) => {
				if (STOPWORDS.indexOf(wordMap[key].word) === -1) {
					wordsArray.push(wordMap[key]);
				}
			});

			//Sort the array by word count in descending order, then alphabetically for equal word counts
			wordsArray.sort(function(a, b) {
				if (parseInt(a.count) < parseInt(b.count)) {
					return 1;
				}
				else if (parseInt(a.count) > parseInt(b.count)) {
					return -1;
				}
				else {
					if (a.word > b.word) {
						return 1;
					}
					return -1;
				}
			});

			//Grab the first "wordsToShow" quantity (or all words in the array if less than)
			this.topWords = [];
			this.topWordCounts = [];
			const len3 = Math.min(this.wordsToShow, wordsArray.length);
			for (let i=0; i<len3; i++) {
				this.topWords.push(wordsArray[i].word);
				this.topWordCounts.push(wordsArray[i].count);
			}

			//Make sure we set the dom canvas height via the wordsToShow length before actually drawing the chart
			this.$nextTick(() => {
				this.drawChart();
			});
		},
		methods: {
			drawChart: function() {
				const ctx = document.getElementById('src'+this.question.id).getContext("2d");

				const chartData = {
					labels: this.topWords,
					datasets: [
						{
							label: null,
							data: this.topWordCounts,
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
