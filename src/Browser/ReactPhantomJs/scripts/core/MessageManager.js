var system = require('system');
var Message = require('./Message');

function MessageManager(streamIn, streamOut) {
	this.streamIn = streamIn;
	this.streamOut = streamOut;
};

MessageManager.prototype = {

	sendMessage: function(message) {
		this.streamOut.write(message.encode());
	},

	waitMessage: function() {
		var line;
		var eol = system.os.name == 'windows' ? "\r\n" : "\n";
		while (line = this.streamIn.readLine()) {
			var message = this.parseMessage(line + eol);
			if (message) {
				return message;
			}
		}
	},

	parseMessage: function(line) {
		var startOfMessage = line.indexOf(Message.ENCODE_PREFIX);
		var endOfMessage = line.indexOf(Message.ENCODE_SUFFIX);
		if (startOfMessage === -1 || endOfMessage === -1) {
			return false;
		}

		var ecnodedMessage = line.substring(startOfMessage, endOfMessage + Message.ENCODE_SUFFIX.length);
		return Message.decode(ecnodedMessage);
	},
};

module.exports = MessageManager;