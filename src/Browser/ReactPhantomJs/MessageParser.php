<?php

namespace RubtsovAV\YandexWordstatParser\Browser\ReactPhantomJs;

use Evenement\EventEmitter;

class MessageParser extends EventEmitter
{
	protected $buffer;

	public function feed($data)
	{
		$this->buffer .= $data;

        $endOfMessage = strpos($this->buffer, Message::ENCODE_SUFFIX);
        if ($endOfMessage === false) {
        	return;
        }

        $message = $this->parseMessage();
        if ($message !== false) {
        	$this->emit('message', [$message]);
        }
        $this->buffer = substr($this->buffer, $endOfMessage + strlen(Message::ENCODE_SUFFIX));
	}

	protected function parseMessage()
	{
		$startOfMessage = strpos($this->buffer, Message::ENCODE_PREFIX);
		$endOfMessage = strpos($this->buffer, Message::ENCODE_SUFFIX, $startOfMessage);
		if ($startOfMessage === false || $endOfMessage === false) {
			return false;
		}

		$messageLength = $endOfMessage - $startOfMessage + strlen(Message::ENCODE_SUFFIX);
		$ecnodedMessage = substr($this->buffer, $startOfMessage, $messageLength);
		return Message::decode($ecnodedMessage);
	}
}