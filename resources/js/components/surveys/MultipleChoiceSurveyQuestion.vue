<template>
	<div class="card">
		<div class="card-header" v-html="(index+1) + '. ' + question.question"></div>
		<div class="card-body">
			<div v-for="(c, i) in JSON.parse(question.choices)" class="form-check">
				<input class="form-check-input" type="checkbox" :id="'q'+index+'r'+i" :value="i" v-model="answer">
				<label class="form-check-label" :for="'q'+index+'r'+i" v-html="c"></label>
			</div>
			<div class="text-danger" v-show="error.length" v-html="error"></div>
		</div>
	</div>
</template>
<script>

	//import { EventBus } from '../EventBus.js';
	export default {
		props: ['question', 'index'],
		data() {
			return {
				error: '',
				answer: []
			}
		},
		created() {
			this.question.answer = [];
		},
		methods: {
			validate: function () {
				this.error = '';
				//TODO this currently allows a user to submit without selecting any option,
				//Uncomment this to force at least one choice
				/*if (!this.answer || !this.answer.length) {
					this.error = 'Please select at least one option';
					return null;
				}*/
				return this.answer;
			},


			//EventBus.$emit('surveySubmitted', data);

		},
	}
</script>
