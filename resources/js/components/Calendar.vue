<template>
	<div>
		<FullCalendar defaultView="dayGridMonth" :header="header" :plugins="calendarPlugins" :events="formattedEvents" @dateClick="handleDateClick" @eventClick="handleEventClick" />
		<div class="mt-4">
			<div v-for="(cal, index) in calendars" v-html="cal.title" v-bind:style="{'background-color': CALENDAR_COLORS[index], 'color': CALENDAR_TEXT_COLORS[index]}" class="p-3">
			</div>
		</div>
	</div>
</template>

<script>
	import FullCalendar from '@fullcalendar/vue';
	import dayGridPlugin from '@fullcalendar/daygrid';
	import interactionPlugin from '@fullcalendar/interaction';
	import timeGridPlugin from '@fullcalendar/timegrid';
	import listPlugin from '@fullcalendar/list';

	//TODO
	//Custom buttons for create if authorized, modal opens?
	//https://fullcalendar.io/docs/customButtons

	//What happens when user clicks on event? goes to url?
	//What happens when user clicks on day? Converts to dayview or listview?


	export default {
		props: ['calendars', 'events'],
		components: {
			'FullCalendar': FullCalendar // make the <FullCalendar> tag available
		},
		data() {
			return {
				CALENDAR_COLORS: ['dodgerblue', 'red', 'green'],
				CALENDAR_TEXT_COLORS: ['white', 'white', 'white'],
				formattedEvents: [],
				calendarPlugins: [ dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin ],
				header: {
					left: 'prev,next today',
					center: 'title',
					right: 'dayGridMonth,timeGridWeek,timeGridDay,listDay'
				}
			}
		},
		mounted() {
			//console.log(this.events);

			for (let i=0; i<this.events.length; i++) {
				const s = this.events[i].starts_at;
				const e = this.events[i].ends_at;

				const isAllDay = s.substr(s.length-8) === "00:00:00" && e.substr(e.length-8) === "23:59:00";

				this.formattedEvents.push({
					'title': this.events[i].title,
					'start': s,
					'end': e,
					'allDay': isAllDay,
					color: this.CALENDAR_COLORS[this.events[i].calendar_id-1],
					textColor: this.CALENDAR_TEXT_COLORS[this.events[i].calendar_id-1],
				});
			}
		},
		methods: {
			handleDateClick(arg) {
				console.log(arg);
			},
			handleEventClick(arg) {
				console.log(arg);
			}
		},
		filters: {
			/*niceDate: function(date) {
				const d = new Date(date.replace(/-/g,"/"));
				return d.toLocaleDateString("en-US");
			}*/
		}
	}
</script>
