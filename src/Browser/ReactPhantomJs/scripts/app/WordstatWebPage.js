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

	setTimeout: function(seconds) {
		this.timeout = seconds;
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

		return new Promise(function(resolve, reject) {
			if (that.timeout) {
				setTimeout(function() {
					reject('timeout');
				}, that.timeout * 1000);
			}

			queryExecutor.execute(query).then(function(result) {
				resolve(result);
			}, function(reason) {
				reject(reason);
			});
		});
		
	},
});

module.exports = WordstatWebPage;