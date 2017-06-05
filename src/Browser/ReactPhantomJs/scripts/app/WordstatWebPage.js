var Promise = require('./../vendor/es6-promise');
var _ = require('./../vendor/lodash');
var WebPage = require('./../core/WebPage');
var WordstatQueryExecutor = require('./WordstatQueryExecutor');

function WordstatWebPage() {
	WebPage.call(this);
}

WordstatWebPage.prototype = _.create(WebPage.prototype, {
	'constructor': WordstatWebPage,

	setYandexUser: function(yandexUser) {
		this.yandexUser = yandexUser;
		return Promise.resolve();
	},

	query: function(query) {
		var that = this;
		var queryExecutor = new WordstatQueryExecutor(this);
		queryExecutor.on('captcha', function(captcha) {
			that.emit('captcha', captcha);
		});
		queryExecutor.on('result', function(result) {
			that.emit('result', result);
		});
		return queryExecutor.execute(query);
	},
});

module.exports = WordstatWebPage;