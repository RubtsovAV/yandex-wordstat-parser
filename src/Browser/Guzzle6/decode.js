/**
 * Decodes the yandex wordstat response
 * 
 * @param  object response     
 * @param  string browserUserAgent 
 * @param  undefined|string cookie The value of the cookie 'fuid01'
 * 
 * @return string
 */
function decode(response, browserUserAgent, cookieValue) {
	var key = [
		browserUserAgent.substr(0, 25), 
		(cookieValue || ''),
		eval(response.key),
	].join('');

	var encryptData = response.data;
	var decryptData = '';
	for (var i = 0; i < encryptData.length; i++) {
		var charCode = encryptData.charCodeAt(i) ^ key.charCodeAt(i % key.length);
		decryptData = decryptData + String.fromCharCode(charCode);
	}

	return decryptData;
}