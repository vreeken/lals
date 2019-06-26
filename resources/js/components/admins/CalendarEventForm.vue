<template>
	<form novalidate @submit="onSubmit">

		<div class="form-group" v-for="(f, i) in formElements" :id="'ig'+i" :key="'ig'+i" :label="f.label" :description="f.description || ''">

			<div v-if="f.type ==='dateTime'">
				<label class="form-check-label" :for="f.name" v-html="f.label"></label>
				<VueCtkDateTimePicker :id="f.name" :label="f.label" v-model="f.value" noLabel format="YYYY-MM-DD HH:mm" v-bind:class="{'invalid': f.touched && f.error !== null, 'valid': f.touched && f.error === null}" @input="onInputHandler(f)"/>
			</div>

			<div v-else-if="f.type == 'checkbox'" class="form-check">
				<input
					class="form-check-input"
					:id="f.name"
					:name="f.name"
					:type="f.type"
					:placeholder="f.label"
					v-model="f.value"
					v-bind:class="{'invalid': f.touched && f.error !== null, 'valid': f.touched && f.error === null}"
					@input="onInputHandler(f)"
				 />
				<label class="form-check-label" :for="f.name" v-html="f.label"></label>
			</div>
			<div v-else>
				<label :for="f.name" v-html="f.label"></label>
				<select
						v-if="f.type == 'select'"
						class="form-control"
						:id="f.name"
						:name="f.name"
						v-model="f.value"
						:options="f.options"
						v-bind:class="{'invalid': f.touched && f.error !== null, 'valid': f.touched && f.error === null}"
						@input="onInputHandler(f)"
				>
					<option value="" >Select a Calendar</option>
					<option v-for="option in f.options" v-bind:value="option.id" >{{ option.title }}</option>
				</select>
				<input
						v-else
						class="form-control"
						:id="f.name"
						:name="f.name"
						:type="f.type"
						:placeholder="f.label"
						v-model="f.value"
						v-bind:class="{'invalid': f.touched && f.error !== null, 'valid': f.touched && f.error === null}"
						@input="onInputHandler(f)"
				 />
			</div>
			<div v-show="f.error" v-html="f.error"></div>
		</div>

		<div class="mb-3 text-danger" v-show="formErrorsString.length" v-html="formErrorsString"></div>
		<button type="submit" class="btn btn-primary" :disabled="ajaxing">Create Event</button>

	</form>
</template>

<script>
	const EMAIL_REGEX = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	const URL_REGEX = /https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/;
	//https://github.com/chronotruck/vue-ctk-date-time-picker?ref=madewithvuejs.com
	//https://chronotruck.github.io/vue-ctk-date-time-picker/
	export default {
		props: ['calendars'],
		data() {
			return {
				formElements: [
					{label: "Event Title", 				type: "text", 		name: "title", 			value: '', 		touched: false, error: null, validator: {required: true}},
					{label: "Calendar",					type: "select", 	name: "calendar_id", 	value: '', 		touched: false, error: null, validator: {required: true}, options: JSON.parse(this.calendars)},
					{label: "Description (Optional)", 	type: "text", 		name: "description", 	value: '', 		touched: false, error: null, validator: {}},
					{label: "URL Link (Optional)", 		type: "text", 		name: "url", 			value: '', 		touched: false, error: null, validator: {type: "url"}},
					{label: "Start Date & Time",		type: "dateTime", 	name: "starts_at", 		value: '', 		touched: false, error: null, validator: {required: true}},
					{label: "End Date & Time",			type: "dateTime", 	name: "ends_at", 		value: '', 		touched: false, error: null, validator: {required: true}},
					//{label: "Is all day?", 			type: "checkbox",	name: "is_all_day", 	value: '', 		touched: false, error: null, validator: {}},
				],
				formErrorsString: '',
				ajaxing: false
			}
		},
		mounted() {

		},
		methods: {
			onSubmit(evt) {
				evt.preventDefault();

				this.formErrorsString = "";

				if (this.validateAll()) {
					this.submit();
				}
				else {
					this.formErrorsString = "There are some errors with your registration data. See above.";
				}
			},
			submit() {
				//Create the submission form data by taking only the name.value pairs from formElements
				let data = {};
				for (let i=0; i<this.formElements.length; i++) {
					data[this.formElements[i].name] = this.formElements[i].value;
				}
				//Flag ajaxing as true to disable the Submit button
				this.ajaxing=true;
				axios.post("/admin/calendar/new", data)
					.then(response => {
						if (response.data && response.data.success) {
							this.$noty.success("Event created successfully");
							for (let i=0; i<this.formElements.length; i++) {
								this.formElements[i].value = '';
								this.formElements[i].touched = false;
							}
						}
						else {
							console.log("Error");
							console.log(response);
							this.$noty.error("Oops, something went wrong! Please try again.");
						}
					})
					.catch(error => {
						if (error.response.status === 422) {
							this.showServerErrors(error.response.data.errors);
						}
						else {
							this.$noty.error("Oops, something went wrong! Please try again.");
						}
						console.log("ERROR");
						console.log(error.response.status);
						console.log(error.response.headers);
						console.log(error.response.data);
					}).then(() => {
						//Set flag to false to re-enable the submit button
						this.ajaxing=false;
					});
			},
			showServerErrors(errors, def='') {
				//List out each error received from the server
				this.formErrorsString = "There were some errors:<br />";
				for (let i=0; i<this.formElements.length; i++) {
					if (errors[this.formElements[i].name]) {
						this.formElements[i].error = errors[this.formElements[i].name][0];
						this.formErrorsString += errors[this.formElements[i].name][0] + '<br />'
					}
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
				});

			},
			validate(formElement) {
				//Reset the error message
				formElement.error = null;
				
				//Make sure a validator object exists for the input element
				if (formElement.validator) {
					const validator = formElement.validator;
					
					//Must not be empty or null
					if (validator.required) {
						if (formElement.value === '' || formElement.value === null) {
							formElement.error = "Please input your " + formElement.label;
							return false;
						}
					}
					
					//Must have a minimum length
					if (validator.min) {
						if (formElement.value.length < validator.min) {
							formElement.error = "Your " + formElement.label + "  must be at least " + validator.min + " characters";
							return false;
						}
					}
					
					//Must be a valid email address
					if (validator.type === "email") {
						//Test against our email regex
						if (!EMAIL_REGEX.test(formElement.value.toLowerCase())) {
							formElement.error = "Please input a valid email address";
							return false;
						}
					}
					
					//Must be a valid phone number
					if (validator.type === "phone") {
						//Strip out all non-numeric characters
						let parsed = String(formElement.value).replace(/\D/g,'');

						//If the number begins with a 1 remove it
						if (parsed.substr(0, 1) === '1') {
							parsed = parsed.substr(1);
						}

						//Make sure we have exactly 10 numeric digits, not including the US country code of +1
						if (parsed.length !== 10) {
							formElement.error = "Please input a valid phone number: 8001234567";
							return false;
						}
					}

					if (validator.type === "url") {
						if (formElement.value.length>0) {
							if (!URL_REGEX.test(formElement.value.toLowerCase())) {
								formElement.error = "Please input a valid url beginning with http:// or https://";
								return false;
							}
						}
					}
					
					//Value must match another form element, commonly used with password and password confirmation
					if (validator.match) {
						let found = false;
						//Loop through all other elements and see if an input with the correct name exists
						for (let i=0; i<this.formElements.length; i++) {
							let f2 = this.formElements[i];
							if (validator.match === f2.name) {
								if (formElement.value !== f2.value) {
									formElement.error = "Your passwords must match";
									return false;
								}
								return true;
							}
						}
						//We didn't find an input element with the specified name.
						//We choose to ignore this error and continue, because it's not the user's fault
						if (!found) {
							console.log('Invalid schema: Cannot find field to match (' + validator.match + ') for ' + formElement.name);
							//return false;
						}
					}
				}
				return true;
			},
			validateAll() {
				//Loop through and validate all inputs, returns true only if all inputs are valid
				let valid = true;
				for (let i=0; i<this.formElements.length; i++) {
					this.formElements[i].touched=true;
					//If any form elements don't validate then set valid to false, but continue through each element
					valid = this.validate(this.formElements[i]) && valid;
				}
				return valid;
			},
		}
	}
</script>
