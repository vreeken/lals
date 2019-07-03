<template>
	<div class="card">
		<div class="card-header" v-html="(index+1) + '. ' + question.question"></div>
		<div class="card-body">
			<div class="form-check">
				<input class="form-check-input" type="radio" :id="'q'+index+'r1'" value="yes" v-model="answer">
				<label class="form-check-label" :for="'q'+index+'r1'">
					Yes
				</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" type="radio" :id="'q'+index+'r2'" value="no" v-model="answer">
				<label class="form-check-label" :for="'q'+index+'r2'">
					No
				</label>
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
					this.error = 'Please select either "yes" or "no"';
					return null;
				}
				return this.answer;
			}

			//EventBus.$emit('surveySubmitted', data);

		}
	}
</script>
