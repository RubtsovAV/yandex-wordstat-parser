var system = require('system');

function Message(type, content) {
	this.type = type;
	this.content = content;
}

Message.ENCODE_PREFIX = '[MESSAGE] ';
Message.ENCODE_SUFFIX = "\n";

Message.prototype = {
	getType: function() {
		return this.type;
	},

	getContent: function() {
		return this.content;
	},

	encode: function() {
		var data = {
			'type': this.getType(),
			'content': this.getContent(),
		};
		return Message.ENCODE_PREFIX + JSON.stringify(data) + Message.ENCODE_SUFFIX;
	},
};

Message.decode = function(line) {
	line = line.substring(Message.ENCODE_PREFIX.length);
	line = line.substring(0, line.length - Message.ENCODE_SUFFIX.length);
	var data = JSON.parse(line);
	return new Message(data['type'], data['content']);
};

module.exports = Message;