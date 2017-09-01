var PhantomJsPage = require("webpage");
var Promise = require('./../vendor/es6-promise');
var _ = require('./../vendor/lodash');
var EventEmitter = require('./../vendor/EventEmitter');

function WebPage() {
	EventEmitter.call(this);
	this.phantomJsPage = PhantomJsPage.create();
}

WebPage.prototype = _.create(EventEmitter.prototype, {
	'constructor': WebPage,

	setViewportSize: function(size) {
		this.phantomJsPage.viewportSize = size;
		return Promise.resolve();
	},

	setHeaders: function(headers) {
		if (_.has(headers, 'User-Agent')) {
			var userAgent = headers['User-Agent'];
			delete headers['User-Agent'];
			this.phantomJsPage.settings.userAgent = userAgent;
		}
		this.phantomJsPage.customHeaders = headers;
		return Promise.resolve();
	},

	setProxy: function(proxy) {
		phantom.setProxy(proxy.host, proxy.port, proxy.type, proxy.username, proxy.password);
		return Promise.resolve();
	},

	setRequestTimeout: function(seconds) {
		this.requestTimeout = seconds;
		return Promise.resolve();
	},

	open: function() {
		var that = this;
		var args = Array.prototype.slice.call(arguments);
		return new Promise(function(resolve, reject){
			if (that.requestTimeout) {
				var timer = setTimeout(function() {
					reject('request timeout');
				}, that.requestTimeout * 1000);
			}
			args.push(function(status) {
				if (timer) {
					clearTimeout(timer);
					timer = null;
				}
				if (status == 'success') {
					resolve(arguments);
				} else if(status == 'fail') {
					reject('Can\'t open uri ' + args[0]);
				} else {
					reject(arguments);
				}
			});
			that.phantomJsPage.open.apply(that.phantomJsPage, args);
		});
	},

	enterTextWithLatencies: function(text, minLatency, maxLatency) {
		if (!minLatency) {
			minLatency = 1000;
		}
		if (!maxLatency) {
			maxLatency = 3000; 
		}
		var that = this;
		var i = 0;
		return new Promise(function(resolve, reject) {
			var pressKey = function() {
				var latency = Math.random() * (maxLatency - minLatency) + minLatency;
				setTimeout(function() {
					that.pressKey(text[i++]).then(function(){
						if (i <= text.length) {
							pressKey();
						} else {
							resolve();
						}
					});
				}, latency);
			};
			pressKey();
		});
	},

	enterText: function(text) {
		for (var i = text.length; i >= 0; i--) {
			this.phantomJsPage.sendEvent('keypress', text[i]);
		}
		return Promise.resolve();
	},

	/**
	 * All key names see here https://github.com/ariya/phantomjs/commit/cab2635e66d74b7e665c44400b8b20a8f225153a
	 * @param  string keyName 
	 */
	pressKey: function(keyName) {
		var keyCode = this.phantomJsPage.event.key[keyName];
		this.phantomJsPage.sendEvent('keypress', keyCode);
		return Promise.resolve();
	},
});

module.exports = WebPage;