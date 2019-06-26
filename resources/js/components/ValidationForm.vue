<link rel="stylesheet" href="../../../../fe/coalition_tech.css">
<template>
	<form novalidate @submit="onSubmit">

		<div class="form-group" v-for="(f, i) in formElements" :id="'ig'+i" :key="'ig'+i" :label="f.label" :description="f.description || ''">

			<div v-if="f.type === 'dateTime'">
				<label class="form-check-label" :for="f.name" v-html="f.label"></label>
				<VueCtkDateTimePicker :id="f.name" :label="f.label" v-model="f.value" noLabel format="YYYY-MM-DD HH:mm" v-bind:class="{'invalid': f.touched && f.error !== null, 'valid': f.touched && f.error === null}" @input="onInputHandler(f)"/>
			</div>

			<div v-else-if="f.type === 'date'">
				<label class="form-check-label" :for="f.name" v-html="f.label"></label>
				<VueCtkDateTimePicker :id="f.name" :label="f.label" v-model="f.value" noLabel format="hh:mm a" formatted="hh:mm a" v-bind:class="{'invalid': f.touched && f.error !== null, 'valid': f.touched && f.error === null}" @input="onInputHandler(f)"/>
			</div>

			<div v-else-if="f.type === 'time'">
				<label class="form-check-label" :for="f.name" v-html="f.label"></label>
				<VueCtkDateTimePicker :id="f.name" :label="f.label" v-model="f.value" noLabel format="YYYY-MM-DD" formatted="ll" v-bind:class="{'invalid': f.touched && f.error !== null, 'valid': f.touched && f.error === null}" @input="onInputHandler(f)"/>
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
			<div v-else-if="f.type == 'select'">
				<label :for="f.name" v-html="f.label"></label>
				<select
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
			</div>
			<div v-else>
				<label :for="f.name" v-html="f.label"></label>
				<input
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

			<div class="mb-3 text-danger" v-show="formErrorsString.length" v-html="formErrorsString"></div>
			<button type="submit" class="btn btn-primary" :disabled="ajaxing" v-html="submitButtonText"></button>

		</div>
	</form>
</template>

<script>
	//TODO extend this component
	//https://vuejsdevelopers.com/2017/06/11/vue-js-extending-components/
	export default {
		data() {
			return {
				EMAIL_REGEX: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
				URL_REGEX: /https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/,

				submitButtonText: 'Submit',

				submitUrl: window.location.href,

				formElements: [
					/*
					{label: "First Name", 		type: "text", 		name: "first_name", 				value: '', 		touched: false, error: null, validator: {required: true}},
					{label: "Last Name", 		type: "text", 		name: "last_name", 					value: '', 		touched: false, error: null, validator: {required: true}},
					{label: "Email", 			type: "email", 		name: "email", 						value: '', 		touched: false, error: null, validator: {required: true, type: "email"}},
					{label: "Username", 		type: "text", 		name: "username", 					value: '', 		touched: false, error: null, validator: {required: true, min: 4}},
					{label: "Password", 		type: "password", 	name: "password", 					value: '', 		touched: false, error: null, validator: {required: true, min: 6}},
					{label: "Confirm Password", type: "password", 	name: "password_confirmation", 		value: '', 		touched: false, error: null, validator: {required: true, match: "password"}},
					{label: "Phone Number", 	type: "text", 		name: "phone", 						value: '', 		touched: false, error: null, validator: {required: true, type: "phone"}},
					{label: "Phone Extension", 	type: "number",		name: "phone_ext",					value: '', 		touched: false, error: null, validator: {}},
					{label: "Address Line 1", 	type: "text", 		name: "address_1", 					value: '', 		touched: false, error: null, validator: {required: true}},
					{label: "Address Line 2", 	type: "text", 		name: "address_2", 					value: '', 		touched: false, error: null, validator: {}},
					{label: "City", 			type: "text", 		name: "city", 						value: '', 		touched: false, error: null, validator: {required: true}},
					{label: "State", 			type: "select", 	name: "state", 						value: null, 	touched: false, error: null, validator: {required: true}, options: STATES},
					{label: "Zip Code", 		type: "text", 		name: "postal_code",				value: '', 		touched: false, error: null, validator: {required: true, min: 5}},
					{label: "Passcode", 		type: "text", 		name: "passcode", 					value: '', 		touched: false, error: null, validator: {required: true}},
					*/
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
				axios.post(this.submitUrl, data)
					.then(response => {
						if (response.data && response.data.success) {
							this.successCallback();
						}
						else {
							console.log("Error");
							console.log(response);
							this.showServerErrors([], "An unknown error occurred. Please try again.");
							this.$noty.error("Oops, something went wrong! Please try again.");
						}
					})
					.catch(error => {
						if (error.response.status === 422) {
							this.showServerErrors(error.response.data.errors);
							this.$noty.error("Oops, something went wrong! Please try again.");
						}
						else {
							this.showServerErrors([], "An unknown error occurred. Please try again.");
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
						if (!this.EMAIL_REGEX.test(formElement.value.toLowerCase())) {
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
							if (!this.URL_REGEX.test(formElement.value.toLowerCase())) {
								formElement.error = "Please input a valid url beginning with http:// or https://";
								return false;
							}
						}
					}

					if (validator.disable_future_dates) {
						if (Dayjs(formElement.value).isAfter(Dayjs())) {
							formElement.error = "You cannot input volunteer hours for a future date";
							return false;
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
