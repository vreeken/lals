<template>
	<div class="card">
		<div class="card-header"><input type="text" class="form-control" name="title" id="title" v-model="title" placeholder="Survey Title" /></div>
		<div class="card-body">

			<div class="form-group">
				<label for="desc">Description</label>
				<textarea class="form-control" id="desc" v-model="description" placeholder="Optional Description"></textarea>
			</div>

			<div class="form-group">
				<div><input v-model="useSchedule" type="checkbox"> Use Scheduled End Time?</div>
				<div v-show="useSchedule">
					<label class="form-check-label" for="ends_at">Survey Ends At</label>
					<VueCtkDateTimePicker id="ends_at" label="Survey Ends At" v-model="endsAt" noLabel format="YYYY-MM-DD HH:mm" />
				</div>
			</div>

			<div class="card mb-3" v-for="(q, i) in questions">
				<div class="card-header d-flex flex-row">
					<button v-show="i !== 0" type="button" class="btn btn-warning mx-2" @click="moveQuestionUp(i)"><span class="d-none d-sm-inline-block mr-3">Move Up </span><i class="fas fa-arrow-up"></i></button>
					<button v-show="i !== questions.length-1" type="button" class="btn btn-warning mx-2" @click="moveQuestionDown(i)"><span class="d-none d-sm-inline-block mr-3">Move Down </span><i class="fas fa-arrow-down"></i></button>

					<button type="button" class="btn btn-danger ml-auto" @click="removeQuestion(i)"><span class="d-none d-sm-inline-block mr-3">Delete Question </span><i class="fas fa-trash-alt"></i></button>
				</div>
				<div class="card-body">
					<div class="form-group position-relative"  :id="'q'+i" :key="'q'+i">
						<label :for="'qq'+i" v-html="'Question ' + (i+1)"></label>
						<input type="text" class="form-control" :id="'qq'+i" v-model="q.question" />

						<label :for="'qt'+i">Type</label>
						<select
								class="form-control"
								:id="'qt'+i"
								v-model="q.type">
							<option value="" >Select a Question Type</option>
							<option v-for="type in questionTypes" v-bind:value="type.id" >{{ type.type }}</option>
						</select>

						<div v-if="q.type >= 10" class="mt-3">
							<h5>Choices</h5>
							<div v-for="(c, j) in q.choices" class="input-group mb-3">
								<input type="text" class="form-control" :placeholder="'Choice '+(j+1)" v-model="c.option"/>
								<div class="input-group-append">
									<div class="input-group">
										<button type="button" class="btn btn-danger survey-form-del-option-btn" @click="removeChoice(q.choices, j)"><i class="fas fa-trash-alt"></i></button>
									</div>
								</div>
							</div>

							<button type="button" class="btn btn-primary mt-3" @click="addMultipleChoiceOption(q.choices)">Add Another Choice</button>
						</div>

						<div v-show="q.error" class="text-danger" v-html="q.error"></div>


					</div>
				</div>
			</div>


			<button type="button" class="btn btn-success mt-5 mb-5" @click="addQuestion">Add Another Question</button>
			<br />
			<div class="mb-3 text-danger" v-show="formErrorsString.length" v-html="formErrorsString"></div>
			<button @click="onSubmit" class="btn btn-primary" :disabled="ajaxing">Create Survey</button>

		</div>
	</div>
</template>
<script>
	//TODO
	//Update chart after submitting hours
	//Allow editing and deleting hours
	//Allow editing time range? Maybe just a cumulative number of hours worked last year
	class Question {
		constructor(question, type, choices, error) {
			this.question = question || '';
			this.type = type || '';
			this.choices = choices || [{option: ''}];
			this.error = error || '';
		}
	}

	import { EventBus } from '../EventBus.js';
	export default {
		props: [],
		data() {
			return {
				title: '',
				description: '',
				useSchedule: true,
				endsAt: null,
				questions: [
					new Question()
				],
				submitUrl: "/members/surveys/create",

				//['text', 'number', 'float', 'yes_no', 'single_choice', 'multiple_choice']
				questionTypes: [
					{
						id: 1,
						type: 'Text'
					},
					{
						id: 2,
						type: 'Number'
					},
					{
						id: 3,
						type: 'Decimal'
					},
					{
						id: 4,
						type: 'Yes/No'
					},
					{
						id: 10,
						type: 'Select Single Choice'
					},
					{
						id: 11,
						type: 'Select Multiple Choice'
					}
				],

				formErrorsString: '',

				ajaxing: false
			}
		},
		mounted() {

		},
		methods: {
			addQuestion() {
				this.questions.push(new Question());
			},
			removeQuestion(i) {
				this.questions.splice(i, 1);
			},
			addMultipleChoiceOption(choices) {
				choices.push({option: ''});
			},
			removeChoice(choices, i) {
				choices.splice(i, 1);
			},
			moveQuestionUp(index) {
				this.questions.splice(index-1, 2, this.questions[index], this.questions[index-1]);
			},
			moveQuestionDown(index) {
				this.questions.splice(index, 2, this.questions[index+1], this.questions[index]);
			},

			onSubmit() {
				if (this.validate()) {
					this.submit();
				}
			},
			validate() {
				let valid = true;
				this.formErrorsString = "";

				for (let i=0; i<this.questions.length; i++) {
					const q = this.questions[i];
					q.error = '';

					if (q.question.length === 0) {
						q.error += 'Please include a question<br />';
						valid=false;
					}

					if (q.type === '') {
						q.error += 'Please select a question type<br />';
						valid=false;
					}

					if (q.type >= 10 && q.choices.length < 2) {
						q.error += 'Please include at least 2 choices for a multiple choice question<br />';
						valid=false;
					}
				}

				if (!valid) {
					this.formErrorsString += 'There are some errors with your questions, see above<br />';
				}

				if (this.title.length === 0) {
					this.formErrorsString += 'Please include a title for the survey<br />';
					valid=false;
				}

				return valid;
			},
			submit() {
				let questions = [];
				for (let i=0; i<this.questions.length; i++) {
					const q = this.questions[i];
					let choices = null;
					if (q.type >= 10) {
						choices = [];
						for (let j = 0; j < q.choices.length; j++) {
							choices.push(q.choices[j].option);
						}
					}
					questions.push({
						q: q.question,
						t: q.type,
						c: choices
					});
				}

				//Flag ajaxing as true to disable the Submit button
				this.ajaxing=true;

				axios.post(this.submitUrl, {
					title: this.title,
					description: this.description.length ? this.description : null,
					questions: JSON.stringify(questions),
					ends_at: this.useSchedule ? this.endsAt : null
				})
					.then(response => {
						if (response.data && response.data.success) {
							//this.successCallback();
							//EventBus.$emit('surveySubmitted', data);
							Notification.success("Survey created");
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
					}).finally(() => {
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
			onInputHandler(formElement) {
				//Set the touched/changed flag
				formElement.touched = true;
				//Validate the input after every input event trigger
				Vue.nextTick(() => {
					this.validate(formElement);

					//If we currently have an invalid form error message, lets loop over every element and see if
					// we're now valid, and if so remove the error msg
					if (this.formErrorsString.length) {
						let valid=true;
						for (let i=0; i<this.formElements.length; i++) {
							if (this.formElements[i].error) {
								valid=false;
								break;
							}
						}
						if (valid) {
							this.formErrorsString = '';
						}
					}

				});

			},
		}
	}
</script>
