<template>
	<b-form novalidate @submit="onSubmit">

		<b-form-group v-for="(f, i) in formElements" :id="'ig'+i" :key="'ig'+i" :label="f.label" :description="f.description || ''">
			<b-form-select
					v-if="f.type == 'select'"
					:id="f.label"
					:name="f.name"
					v-model="f.value"
					:options="f.options"
					v-bind:class="{'invalid': f.touched && f.error !== null, 'valid': f.touched && f.error === null}"
					@input="onInputHandler(f)"
			></b-form-select>
			<b-form-input
					v-else
					:id="f.name"
					:name="f.name"
					:type="f.type"
					:placeholder="f.label"
					v-model="f.value"
					v-bind:class="{'invalid': f.touched && f.error !== null, 'valid': f.touched && f.error === null}"
					@input="onInputHandler(f)"
			></b-form-input>
			<div v-show="f.error" v-html="f.error"></div>
		</b-form-group>

		<b-button type="submit" variant="primary">Register</b-button>

	</b-form>
</template>

<script>

	//import { EventBus } from './eventbus/EventBus.js';
	const EMAIL_REGEX = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	const STATES = [{text:'Select State', value: null },{"text":"Alabama","value":"AL"},{"text":"Alaska","value":"AK"},{"text":"American Samoa","value":"AS"},{"text":"Arizona","value":"AZ"},{"text":"Arkansas","value":"AR"},{"text":"California","value":"CA"},{"text":"Colorado","value":"CO"},{"text":"Connecticut","value":"CT"},{"text":"Delaware","value":"DE"},{"text":"District Of Columbia","value":"DC"},{"text":"Federated States Of Micronesia","value":"FM"},{"text":"Florida","value":"FL"},{"text":"Georgia","value":"GA"},{"text":"Guam","value":"GU"},{"text":"Hawaii","value":"HI"},{"text":"Idaho","value":"ID"},{"text":"Illinois","value":"IL"},{"text":"Indiana","value":"IN"},{"text":"Iowa","value":"IA"},{"text":"Kansas","value":"KS"},{"text":"Kentucky","value":"KY"},{"text":"Louisiana","value":"LA"},{"text":"Maine","value":"ME"},{"text":"Marshall Islands","value":"MH"},{"text":"Maryland","value":"MD"},{"text":"Massachusetts","value":"MA"},{"text":"Michigan","value":"MI"},{"text":"Minnesota","value":"MN"},{"text":"Mississippi","value":"MS"},{"text":"Missouri","value":"MO"},{"text":"Montana","value":"MT"},{"text":"Nebraska","value":"NE"},{"text":"Nevada","value":"NV"},{"text":"New Hampshire","value":"NH"},{"text":"New Jersey","value":"NJ"},{"text":"New Mexico","value":"NM"},{"text":"New York","value":"NY"},{"text":"North Carolina","value":"NC"},{"text":"North Dakota","value":"ND"},{"text":"Northern Mariana Islands","value":"MP"},{"text":"Ohio","value":"OH"},{"text":"Oklahoma","value":"OK"},{"text":"Oregon","value":"OR"},{"text":"Palau","value":"PW"},{"text":"Pennsylvania","value":"PA"},{"text":"Puerto Rico","value":"PR"},{"text":"Rhode Island","value":"RI"},{"text":"South Carolina","value":"SC"},{"text":"South Dakota","value":"SD"},{"text":"Tennessee","value":"TN"},{"text":"Texas","value":"TX"},{"text":"Utah","value":"UT"},{"text":"Vermont","value":"VT"},{"text":"Virgin Islands","value":"VI"},{"text":"Virginia","value":"VA"},{"text":"Washington","value":"WA"},{"text":"West Virginia","value":"WV"},{"text":"Wisconsin","value":"WI"},{"text":"Wyoming","value":"WY"}];

	export default {
		data() {
			return {
				formElements: [
					{label: "First Name", 		type: "text", 		name: "first_name", 				value: '', 		touched: false, error: null, validator: {required: true}},
					{label: "Last Name", 		type: "text", 		name: "last_name", 					value: '', 		touched: false, error: null, validator: {required: true}},
					{label: "Email", 			type: "email", 		name: "email", 						value: '', 		touched: false, error: null, validator: {required: true, type: "email"}},
					{label: "Username", 		type: "text", 		name: "username", 					value: '', 		touched: false, error: null, validator: {required: true, min: 4}},
					{label: "Password", 		type: "password", 	name: "password", 					value: '', 		touched: false, error: null, validator: {required: true, min: 6}},
					{label: "Confirm Password", type: "password", 	name: "password_confirmation", 		value: '', 		touched: false, error: null, validator: {required: true, match: "password"}},
					{label: "Phone Number", 	type: "text", 		name: "phone", 						value: '', 		touched: false, error: null, validator: {required: true, type: "phone"}},
					{label: "Phone Extension", 	type: "number",		name: "phone_ext",					value: '', 		touched: false, error: null, validator: {required: true}},
					{label: "Address Line 1", 	type: "text", 		name: "addr_1", 					value: '', 		touched: false, error: null, validator: {required: true}},
					{label: "Address Line 2", 	type: "text", 		name: "addr_2", 					value: '', 		touched: false, error: null, validator: {}},
					{label: "Address Line 3", 	type: "text", 		name: "addr_3", 					value: '', 		touched: false, error: null, validator: {}},
					{label: "City", 			type: "text", 		name: "city", 						value: '', 		touched: false, error: null, validator: {required: true}},
					{label: "State", 			type: "select", 	name: "state", 						value: null, 	touched: false, error: null, validator: {required: true}, options: STATES},
					{label: "Zip Code", 		type: "text", 		name: "zip", 						value: '', 		touched: false, error: null, validator: {required: true, min: 5}},
					{label: "Passcode", 		type: "text", 		name: "passcode", 					value: '', 		touched: false, error: null, validator: {required: true}},
				]
			}
		},
		mounted() {

		},
		methods: {
			onSubmit(evt) {
				evt.preventDefault();
				
				if (this.validateAll()) {
					this.submit();
				}
				else {
					console.log('invalid');
				}
			},
			submit() {
				let data = {};
				for (let i=0; i<this.formElements.length; i++) {
					data[this.formElements[i].name] = this.formElements[i].value;
				}

				axios.post("/register", data)
					.then(function(response) {
						if (response.data && response.data.success) {

						}
						else {
							console.log("Error");
							console.log(response);
						}
					})
					.catch(function(error) {
						console.log("ERROR");
						console.log(error);
						console.log(error.response.headers);
						console.log(error.response.data);
						//invalid_parameters
						//db_error
						//_this.newComment.ajaxError = "An error has occurred. Please try again.";
					});
			},
			onInputHandler(formElement) {
				//Set the touched/changed flag
				formElement.touched = true;
				//Validate the input after every input event trigger
				this.validate(formElement);
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
		},
		computed: {

		}
	}
</script>




<style>

</style>
