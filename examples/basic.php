<?php

	include_once __DIR__ . '/../vendor/autoload.php';

    use RubtsovAV\YandexWordstatParser\Parser;
    use RubtsovAV\YandexWordstatParser\Query;
    use RubtsovAV\YandexWordstatParser\YandexUser;
    use RubtsovAV\YandexWordstatParser\CaptchaInterface;
    use RubtsovAV\YandexWordstatParser\Browser\Guzzle6 as Browser;
    use RubtsovAV\YandexWordstatParser\Proxy\Http as HttpProxy;

    $yandexUser = new YandexUser('login', 'password', __DIR__ . '/storage');
    $proxy = new HttpProxy('192.168.1.1', 80); 

    $browser = new Browser(false); // True for ignoring an SSL error for the traffic sniffer
    $browser->setProxy($proxy);
    $browser->setRequestTimeout(60);   // in seconds
    $browser->setCaptchaSolver(function($captcha){
        $image = file_get_contents($captcha->getImageUri());
        file_put_contents(__DIR__ . '/captcha.jpg', $image);
        file_put_contents(__DIR__ . '/captchaAnswer.txt', '');

        echo "The captcha image was save to captcha.jpg. Write the answer in captchaAnswer.txt\n";
        $answer = '';
        while (!$answer) {
            $answer = file_get_contents(__DIR__ . '/captchaAnswer.txt');
            $answer = trim($answer);
            sleep(1);
        }
        echo "The captcha answer is '$answer'\n";
        $captcha->setAnswer($answer);
        return true;
    });

    $parser = new Parser($browser, $yandexUser);

    $query = new Query('купить диван');
    $result = $parser->query($query);

    print_r($result->toArray());
