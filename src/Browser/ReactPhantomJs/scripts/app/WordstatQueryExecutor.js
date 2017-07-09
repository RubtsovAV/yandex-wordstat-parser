var Promise = require('./../vendor/es6-promise');
var _ = require('./../vendor/lodash');
var EventEmitter = require('./../vendor/EventEmitter');
var WordstatCaptcha = require('./WordstatCaptcha');

function WordstatQueryExecutor(webPage) {
	EventEmitter.call(this);
	this.webPage = webPage;
}

WordstatQueryExecutor.prototype = _.create(EventEmitter.prototype, {
	'constructor': WordstatQueryExecutor,

	execute: function(query) {
		var that = this;
		var url = this.buildUri(query);

		this.webPage.open(url)
			.then(function() {
				if (!that.isYandexWordstat()) {
					return Promise.reject('not valid wordstat page');
				}
				return Promise.resolve();
			})
			.then(function() {
				return that.waitLoading();
			})
			.then(function() {
				return that.collectWords();
			})
			.then(function(result) {
				that.emit('result', result);
			}, function(reason) {
				that.emit('error', reason);
			});

		return new Promise(function(resolve, reject) {
			that.on('result', function() {
				resolve();
			});
			that.on('error', function(reason) {
				reject(reason);
			});
		});
	},

	buildUri: function(query) {
		var queryData = {'words': query.words};
		if (_.isArray(query.regions)) {
			queryData.regions = query.regions.join(',');
		}
		if (query.pageNumber > 1) {
			queryData.page = query.pageNumber;
		}
		return 'https://wordstat.yandex.ru/#!/?' + this.encodeQueryData(queryData);
	},

	encodeQueryData: function(data) {
		var ret = [];
		for (var d in data) {
			ret.push(encodeURIComponent(d) + '=' + encodeURIComponent(data[d]));
		}
		return ret.join('&');
	},

	collectWords: function() {
		var that = this;
		if (!that.isLogin()) {
			return that.login().then(function() {
				return that.collectWords();
			});
		} 
		if (that.isCaptcha()) {
			return that.solveCaptcha().then(function() {
				return that.collectWords();
			});
		} 
		if (that.isUserBanned()) {
			return Promise.reject('the yandex user is banned');
		}
		return that.parseWords();
	},

	isYandexWordstat: function() {
		return this.getPhantomJsPage().evaluate(function() {
    		return $('.b-wordstat-content').length > 0;
    	});
	},

	isLogin: function() {
		return this.getPhantomJsPage().evaluate(function() {
    		return $('#b-domik_popup-username').length == 0;
    	});
	},

	login: function() {
		this.getPhantomJsPage().evaluate(function (username, password) {
    		var $username = $('#b-domik_popup-username');
    		var $password = $('#b-domik_popup-password');
    		var $form = $username.closest('form');

    		$username.val(username);
    		$password.val(password);
    		$form.submit();
    	}, this.getYandexUser().login, this.getYandexUser().password);

		var that = this;
		return new Promise(function(resolve, reject) {
			that.getPhantomJsPage().onLoadFinished = function() {
				resolve(that.waitLoading());
			};
		});
    	return ;
	},

	isCaptcha: function() {
		return this.getPhantomJsPage().evaluate(function() {
			return $('.b-page__captcha-popup').is(':visible');
		});
	},

	solveCaptcha: function() {
		var that = this;
		return this.getCaptcha()
			.then(function(captcha) {
				return that.sendCaptha(captcha);
			})
			.then(function(captcha) {
				return that.enterCaptchaAnswer(captcha.getAnswer());
			});
	},

	getCaptcha: function() {
		return this.getCaptchaImageUri().then(function(captchaImageUri){
			var captcha = new WordstatCaptcha(captchaImageUri);
			return Promise.resolve(captcha);
		});
	},

	getCaptchaImageUri: function() {
		var that = this;
		var imageUri = this.getPhantomJsPage().evaluate(function() {
			return $('.b-page__captcha-popup .b-popupa__image')[0].src;
		});
		if (!imageUri) {
			throw new Error('The captcha image uri undefined');
		}
		return Promise.resolve(imageUri);
	},

	sendCaptha: function(captcha) {
		this.emit('captcha', captcha);
		return Promise.resolve(captcha);
	},

	enterCaptchaAnswer: function(captchaAnswer) {
		var that = this;
		return that.webPage
			.enterTextWithLatencies(captchaAnswer, 100, 200)
			.then(function() {
				return that.webPage.pressKey('Enter');
			})
			.then(function() {
				return that.waitLoading();
			});
	},

	isUserBanned: function() {
		return this.getPhantomJsPage().evaluate(function() {
			return $('.control__input_name_history-answer').length > 0;
		});
	},

	waitLoading: function() {
		var that = this;
		var i = 0;
		return new Promise(function(resolve, reject) {
			if (that.webPage.timeout) {
				var timer = setTimeout(function() {
					reject('wait timeout');
				}, that.webPage.timeout * 1000);
			}
			var wait = function() {
				setTimeout(function(){
					if (that.isLoading()) {
						wait();
					} else {
						if (timer) {
							clearTimeout(timer);
							timer = null;
						}
						resolve();
					}
				}, 250);
			};
			wait();
		});
	},

	isLoading: function() {
		return this.getPhantomJsPage().evaluate(function() {
			return $('.b-page__load-popup').is(':visible');
		});
	},

	parseWords: function() {
        var result = this.getPhantomJsPage().evaluate(function() {
        	var impressions = $('.b-word-statistics__info:first')
        		.html()
        		.replace(/^.*&nbsp;—&nbsp;([0-9]+)/, '$1')
        		.replace(/[^0-9]/g, '');
        	impressions = parseInt(impressions);

        	var includingPhrases = {};
        	$('.b-word-statistics__including-phrases .b-word-statistics__tr:gt(0)').each(function() {
        		var phrase = $('.b-phrase-link__link', this).text();
        		var impressions = $('.b-word-statistics__td-number', this).text();
        		impressions = impressions.replace(/[^0-9]/g, '');
        		includingPhrases[phrase] = parseInt(impressions);
        	});

        	var phrasesAssociations = {};
        	$('.b-word-statistics__phrases-associations .b-word-statistics__tr:gt(0)').each(function() {
        		var phrase = $('.b-phrase-link__link', this).text();
        		var impressions = $('.b-word-statistics__td-number', this).text();
        		impressions = impressions.replace(/[^0-9]/g, '');
        		phrasesAssociations[phrase] = parseInt(impressions);
        	});

        	var lastUpdate = $('.b-word-statistics__last-update:first')
        		.text()
        		.replace(/^.*: ([0-9\.]+)$/, '$1');

        	// convert date to timestamp
        	var date = lastUpdate.split('.');
        	lastUpdate = Math.floor((new Date(date[2], date[1] - 1, date[0])).valueOf() / 1000);

        	var nextPageExists = $('.b-pager__next').parent().is('.b-pager__active');

            return {
            	impressions: impressions,
            	includingPhrases: includingPhrases,
            	phrasesAssociations: phrasesAssociations,
            	lastUpdate: lastUpdate,
            	nextPageExists: nextPageExists,

            };
        });
        return Promise.resolve(result);
	},

	getPhantomJsPage: function() {
		return this.webPage.phantomJsPage;
	},

	getYandexUser: function() {
		return this.webPage.yandexUser;
	},
});

module.exports = WordstatQueryExecutor;