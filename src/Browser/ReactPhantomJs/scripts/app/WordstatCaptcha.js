var Promise = require('./../vendor/es6-promise');
var _ = require('./../vendor/lodash');
var EventEmitter = require('./../vendor/EventEmitter');

function WordstatCaptcha(imageUri) {
	EventEmitter.call(this);
	this.imageUri = imageUri;
}

WordstatCaptcha.prototype = _.create(EventEmitter.prototype, {
	'constructor': WordstatCaptcha,

	getImageUri: function() {
		return this.imageUri;
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