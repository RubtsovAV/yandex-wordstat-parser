<?php

	include_once __DIR__ . '/../vendor/autoload.php';

    use RubtsovAV\YandexWordstatParser\Parser;
    use RubtsovAV\YandexWordstatParser\Query;
    use RubtsovAV\YandexWordstatParser\YandexUser;
    use RubtsovAV\YandexWordstatParser\CaptchaInterface;
    use RubtsovAV\YandexWordstatParser\Browser\ReactPhantomJs;
    use RubtsovAV\YandexWordstatParser\Proxy\Http as HttpProxy;

    $yandexUser = new YandexUser('test12345678902017', 'test1234567890', __DIR__ . '/storage');
    $proxy = new HttpProxy('1.179.198.17', 8080); 

    $browser = new ReactPhantomJs();
    $browser->setProxy($proxy);
    $browser->setTimeout(60);   // in seconds
    $browser->setCaptchaSolver(function($captcha){
        $image = $captcha->getBinaryImage();
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
