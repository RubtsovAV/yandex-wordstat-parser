var Promise = require('./../vendor/es6-promise');
var _ = require('./../vendor/lodash');
var EventEmitter = require('./../vendor/EventEmitter');

function WordstatCaptcha(base64Image) {
	EventEmitter.call(this);
	this.image = base64Image;
}

WordstatCaptcha.prototype = _.create(EventEmitter.prototype, {
	'constructor': WordstatCaptcha,

	getImage: function() {
		return this.image;
	},

	setAnswer: function(answer) {
		this.answer = answer;
		this.emit('answer', answer);
	},

	getAnswer: function() {
		return this.answer;
	},
});

module.exports = WordstatCaptcha;