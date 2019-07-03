<template>
	<div class="card">
		<div class="card-header" v-html="survey.title"></div>
		<div class="card-body">

			<div v-if="!showingResults">
				<div v-if="survey.description && survey.description.length" class="mb-3" v-html="survey.description"></div>

				<component v-for="(q, i) in survey.questions" :ref="'questionComponent'+i" :question="q" :key="'question'+i" :index="i" v-bind:is="TYPES[q.question_type]['component']" class="mb-3"></component>

				<div class="mb-3 text-danger" v-show="surveyErrorsString.length" v-html="surveyErrorsString"></div>
				<button @click="onSubmit" class="btn btn-primary" :disabled="ajaxing">Submit</button>
			</div>

			<div v-else-if="showingResults && !rawData">
				<div v-for="(res, i) in results">
					<div v-html="(i+1) + '. ' + survey.questions[i].question"></div>
					<component  :ref="'resultComponent'+i" :question="survey.questions[i]" :answers="res" @showRawDataPopup="showRawDataPopup($event)" :key="'result'+i" :index="i" v-bind:is="TYPES[survey.questions[i].question_type]['resultComponent']" class="my-4"></component>
					<button class="btn btn-success" @click="showRawDataPopup(res)">Show me the raw data</button>
					<hr>
				</div>
			</div>
			<div v-else-if="showingResults && rawData" style="position: relative;">
				<button @click="rawData=null;" class="btn btn-danger" style="position: absolute; top: 0; right: 0"><i class="fas fa-times"></i></button>
				<pre>{{ rawData }}</pre>
			</div>
		</div>
	</div>
</template>
<script>
	//TODO

	import TextSurveyQuestion from './TextSurveyQuestion';
	import NumberSurveyQuestion from './NumberSurveyQuestion';
	import DecimalSurveyQuestion from './DecimalSurveyQuestion';
	import YesNoSurveyQuestion from './YesNoSurveyQuestion';
	import SingleChoiceSurveyQuestion from './SingleChoiceSurveyQuestion';
	import MultipleChoiceSurveyQuestion from './MultipleChoiceSurveyQuestion';

	import TextSurveyChart from './TextSurveyChart';
	import YesNoSurveyChart from './YesNoSurveyChart';
	import DecimalSurveyChart from './DecimalSurveyChart';
	import NumberSurveyChart from './NumberSurveyChart';
	import SingleChoiceSurveyChart from './SingleChoiceSurveyChart';
	import MultipleChoiceSurveyChart from './MultipleChoiceSurveyChart';

	//import { EventBus } from '../EventBus.js';
	export default {
		props: ['survey'],
		components: {
			TextSurveyQuestion, NumberSurveyQuestion, DecimalSurveyQuestion,
			YesNoSurveyQuestion, SingleChoiceSurveyQuestion, MultipleChoiceSurveyQuestion,
			YesNoSurveyChart, DecimalSurveyChart, NumberSurveyChart, SingleChoiceSurveyChart,
			MultipleChoiceSurveyChart, TextSurveyChart
		},
		data() {
			return {
				TYPES: {
					1: {pretty: 'Text', component: 'TextSurveyQuestion', resultComponent: 'TextSurveyChart'},
					2: {pretty: 'Number', component: 'NumberSurveyQuestion', resultComponent: 'NumberSurveyChart'},
					3: {pretty: 'Decimal', component: 'DecimalSurveyQuestion', resultComponent: 'DecimalSurveyChart'},
					4: {pretty: 'Yes/No', component: 'YesNoSurveyQuestion', resultComponent: 'YesNoSurveyChart'},
					10: {pretty: 'Select Single Choice', component: 'SingleChoiceSurveyQuestion', resultComponent: 'SingleChoiceSurveyChart'},
					11: {pretty: 'Select Multiple Choice', component: 'MultipleChoiceSurveyQuestion', resultComponent: 'MultipleChoiceSurveyChart'},
				},

				submitUrl: window.location,

				surveyErrorsString: '',

				ajaxing: false,
				answers: [],

				showingResults: false,
				results: [],
				rawData: null
			}
		},
		mounted() {

		},
		methods: {

			showRawDataPopup(raw) {
				this.rawData = JSON.stringify(raw, null, 2);
			},


			onSubmit() {
				if (this.validate()) {
					this.submit();
				}
			},
			validate() {
				let valid = true;
				this.surveyErrorsString = "";
				this.answers = [];

				for (let i=0; i<this.survey.questions.length; i++) {
					const answer = this.$refs['questionComponent'+i][0].validate();
					if (answer === null) {
						valid=false;
					}
					this.answers.push({qid: this.survey.questions[i].id, answer: answer});
				}

				if (!valid) {
					this.surveyErrorsString = "Oops! There are some errors with your answers. See above.";
				}

				return valid;
			},
			submit() {
				//Flag ajaxing as true to disable the Submit button
				this.ajaxing=true;

				axios.post(this.submitUrl, {
					survey_id: this.survey.survey_id,
					answers: JSON.stringify(this.answers),
				})
					.then(response => {
						if (response.data && response.data.success) {
							//this.successCallback();
							//EventBus.$emit('surveySubmitted', data);
							Notification.success("Thanks for taking our survey");

							if (response.data.results) {
								this.parseResults(response.data.results);
							}
						}
						else {
							console.log("Error");
							console.log(response);
							this.showServerErrors([], "An unknown error occurred. Please try again.");
							Notification.error("Oops, something went wrong! Please try again.");
						}
					})
					.catch(error => {
						if (error.response.status === 422) {
							this.showServerErrors(error.response.data.errors);
							Notification.error("Oops, something went wrong! Please try again.");
						}
						else {
							this.showServerErrors([], "An unknown error occurred. Please try again.");
							Notification.error("Oops, something went wrong! Please try again.");
						}
						console.log("ERROR");
						console.log(error.response.status);
						console.log(error.response.headers);
						console.log(error.response.data);
					})
					.finally(() => {
					//Set flag to false to re-enable the submit button
					this.ajaxing=false;
				});
			},
			showServerErrors(errors, def='') {
				//List out each error received from the server
				this.formErrorsString = "There were some errors:<br />";

				for (let i=0; i<errors; i++) {
					this.formErrorsString += errors[i][0] + '<br />';
				}


				//If we have a default error string supplied, append it
				if (def) {
					this.formErrorsString += def;
				}
			},
			parseResults(res) {
				try {
					const qidOffset = res[0].survey_question_id;

					for (let i = 0; i < this.survey.questions.length; i++) {
						this.results.push([]);
					}
					for (let i = 0; i < res.length; i++) {
						this.results[res[i].survey_question_id - qidOffset].push(res[i]);
					}


					this.showingResults = true;
				}
				catch (e) {
					console.log(e);
					console.log('Malformed Results from Server');
				}
			},
		}
	}
</script>
