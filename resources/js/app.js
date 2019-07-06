/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

//https://github.com/chronotruck/vue-ctk-date-time-picker?ref=madewithvuejs.com
//https://chronotruck.github.io/vue-ctk-date-time-picker/
import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';


// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('registration-form', require('./components/forms/RegistrationForm.vue').default);

Vue.component('flash', require('./components/Flash.vue').default);
Vue.component('invoice', require('./components/members/Invoice.vue').default);
Vue.component('invoices', require('./components/members/Invoices.vue').default);

Vue.component('calendar', require('./components/Calendar.vue').default);
Vue.component('volunteer-hours-chart', require('./components/members/VolunteerHoursChart.vue').default);
Vue.component('volunteer-hours-form', require('./components/forms/VolunteerHoursForm.vue').default);

Vue.component('validation-form-base', require('./components/forms/ValidationFormBase.vue').default);


Vue.component('survey-creation-form', require('./components/forms/SurveyCreationForm.vue').default);
Vue.component('survey', require('./components/surveys/Survey.vue').default);


Vue.component('calendar-event-form', require('./components/forms/CalendarEventForm.vue').default);
Vue.component('VueCtkDateTimePicker', VueCtkDateTimePicker);
//Vue.component('wysiwyg', require('./components/Wysiwyg.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app'
});
