
phantom.onError = function(msg, trace) {
	var msgStack = ['PHANTOM ERROR: ' + msg];
	if (!trace && msg.stack) {
		msgStack.push('TRACE:');
		msgStack.push(JSON.stringify(msg.stack.split("\n"), null, 4));
	}
	if (trace && trace.length) {
		msgStack.push('TRACE:');
		var t = trace[0];
		msgStack.push(' -> ' + (t.file || t.sourceURL) + ': ' + t.line + (t.function ? ' (in function ' + t.function +')' : ''));
	}
	system.stderr.write(msgStack.join('\n'));
	phantom.exit(1);
};

var system = require('system');
var fs = require('fs');
var Message = require('./core/Message');
var MessageManager = require('./core/MessageManager');
var MessageHandler = require('./core/MessageHandler');
var WordstatWebPage = require("./app/WordstatWebPage");

var wordstatWebPage = new WordstatWebPage();
var messageManager = new MessageManager(system.stdin, system.stdout);
var messageHandler = new MessageHandler(wordstatWebPage);

wordstatWebPage.on('message', function(message) {
	messageManager.sendMessage(message);
});

wordstatWebPage.on('captcha', function(captcha) {
	var message = new Message('captcha', captcha.getImageUri());
	messageManager.sendMessage(message);
	try {
		var message = messageManager.waitMessage();
		if (message.getType() == 'captchaAnswer') {
			captcha.setAnswer(message.getContent());
		} else {
			throw new Error('expected the captchaAnswer type, but "' + message.getType() + '" was received');
		}
	} catch (e) {
		phantom.onError(e.message);
	}
});

wordstatWebPage.on('result', function(result) {
	var message = new Message('result', result);
	messageManager.sendMessage(message);
});

function lifecycle() {
	try {
		var message = messageManager.waitMessage();
		if (message.getType() == 'terminate') {
			phantom.exit(0);
			return;
		}
		messageHandler.handle(message).then(lifecycle, phantom.onError);
	} catch (e) {
		phantom.onError(e.message);
	}
}
lifecycle();


