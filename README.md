# Yandex Wordstat Parser

## How it use

Install phantomjs [http://phantomjs.org/download.html]
```
<?php

    use RubtsovAV\YandexWordstatParser\Parser;
    use RubtsovAV\YandexWordstatParser\Query;
    use RubtsovAV\YandexWordstatParser\YandexUser;
    use RubtsovAV\YandexWordstatParser\CaptchaInterface;
    use RubtsovAV\YandexWordstatParser\Browser\ReactPhantomJs;
    use RubtsovAV\YandexWordstatParser\Proxy\Http as HttpProxy;

    $yandexUser = new YandexUser('test12345678902017', 'test1234567890', __DIR__ . '/storage');
    $proxy = new HttpProxy('1.179.198.17', 8080); 

    $browser = new ReactPhantomJs();
    $browser->setProxy($proxy); // optional
    $browser->setTimeout(60);   // in seconds (120 by default)
    $browser->setCaptchaSolver(function($captcha) {
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

```
## Output

```
Array
(
    [impressions] => 579
    [includingPhrases] => Array
        (
            [где купить диван] => 17472
            [диван аккордеон купить] => 13673
            [диван книжка купить] => 11062
            [кресло диван купить] => 14594
            [купить +в магазине диван недорого] => 7691
            [купить выкатной диван] => 5835
            [купить детский диван] => 11660
            [купить диван] => 579537
            [купить диван +в екатеринбурге] => 8440
            [купить диван +в интернет] => 20241
            [купить диван +в интернет магазине] => 19355
            [купить диван +в интернет магазине недорого] => 7094
            [купить диван +в магазине] => 23029
            [купить диван +в минске] => 8315
            [купить диван +в москве] => 54800
            [купить диван +в москве +от производителя] => 6646
            [купить диван +в спб] => 33749
            [купить диван +в спб +от производителя] => 6525
            [купить диван +в спб недорого] => 9704
            [купить диван +на авито] => 14742
            [купить диван +на кухню] => 7597
            [купить диван +от производителя] => 23858
            [купить диван +от производителя распродажа] => 6880
            [купить диван б +у] => 15408
            [купить диван бу] => 18005
            [купить диван дешево] => 21605
            [купить диван еврокнижка] => 10512
            [купить диван кровать] => 35993
            [купить диван недорого] => 94669
            [купить диван недорого +в москве] => 20606
            [купить диван недорого +в москве распродажа] => 11636
            [купить диван недорого +от производителя] => 14271
            [купить диван недорого распродажа] => 18358
            [купить диван распродажа] => 25659
            [купить диван со] => 11725
            [купить диван со спальным местом] => 5821
            [купить диван спальным местом] => 7180
            [купить диван трансформер] => 6487
            [купить диван цена] => 14317
            [купить кожаный диван] => 12092
            [купить ортопедический диван] => 7553
            [купить прямой диван] => 6719
            [купить раскладной диван] => 7318
            [купить спальный диван] => 8170
            [купить угловой диван] => 71464
            [купить угловой диван +в москве] => 7438
            [купить угловой диван недорого] => 12567
            [купить чехол +на диван] => 16730
            [мебель купить диван] => 8087
            [недорогие диваны купить +в интернете] => 7301
        )

    [phrasesAssociations] => Array
        (
            [дешевый диван] => 50740
            [диван 2 2] => 34786
            [диван еврокнижка] => 47708
            [диван интернет магазин] => 82189
            [диван каталог] => 130962
            [диван кровать] => 167868
            [диван производитель] => 58525
            [диван распродажа] => 88032
            [диван руб] => 1791
            [диван цена] => 163024
            [купить кровать] => 474460
            [купить мебель] => 402101
            [купить мягкий мебель] => 25958
            [магазин диван] => 120989
            [мебель диван] => 115807
            [мягкий мебель] => 258892
            [недорогой диван] => 168954
            [недорогой угловой диван] => 25162
            [угловой диван] => 337282
            [хороший диван] => 17278
        )

    [lastUpdate] => 1496620800
    [nextPageExists] => 1
)
```

