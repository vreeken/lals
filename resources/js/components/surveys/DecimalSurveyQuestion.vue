<template>
	<div class="card">
		<div class="card-header" v-html="(index+1) + '. ' + question.question"></div>
		<div class="card-body">
			<div class="form-group">
				<label :for="'q'+index">Enter your answer</label>
				<input type="number" step=".01" class="form-control" :id="'q'+index" v-model="answer" placeholder="Enter a number" />
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
				answer: null
			}
		},
		mounted() {

		},
		methods: {
			validate: function () {
				this.error = '';
				if (!this.answer || !this.answer.length) {
					this.error = 'Please enter a valid decimal number';
					return null;
				}
				return this.answer;
			}
			//EventBus.$emit('surveySubmitted', data);

		}
	}
</script>
