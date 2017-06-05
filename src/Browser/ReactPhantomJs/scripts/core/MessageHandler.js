var _ = require('./../vendor/lodash');
var system = require('system');

function MessageHandler(webPage)
{
	this.webPage = webPage;
}

MessageHandler.prototype = {
	handle: function(message) {
		var methodName = message.getType();
		var method = this.webPage[methodName];
		if (!_.isFunction(method)) {
			throw new Error("method '" + methodName + "' is undefined");
		}
		return method.call(this.webPage, message.getContent());
	},
};

module.exports = MessageHandler;