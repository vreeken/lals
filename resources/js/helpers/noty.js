window.Noty = require('noty');
window.Noty.overrideDefaults({
	layout   : 'bottomCenter',
	theme    : 'metroui',
	closeWith: ['click', 'button'],
	timeout: 5000,
	animation: {
		open : 'animated fadeInUp',
		close: 'animated fadeOutDown'
	}
});


window.Notification = {
	alert: function(msg) {
		new Noty({
			type: 'alert',
			text: msg
		}).show();
	},
	success: function(msg) {
		new Noty({
			type: 'success',
			text: msg
		}).show();
	},
	warning: function(msg) {
		new Noty({
			type: 'warning',
			text: msg
		}).show();
	},
	error: function(msg) {
		new Noty({
			type: 'error',
			text: msg,
		}).show();
	},
	info: function(msg) {
		new Noty({
			type: 'info',
			text: msg
		}).show();
	},
};