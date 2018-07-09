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

```
## Output

```
Array
(
    [impressions] => 652525
    [includingPhrases] => Array
        (
            [0] => Array
                (
                    [impressions] => 652525
                    [words] => купить диван
                )

            [1] => Array
                (
                    [impressions] => 105444
                    [words] => купить диван недорого
                )

            [2] => Array
                (
                    [impressions] => 79061
                    [words] => купить угловой диван
                )

            [3] => Array
                (
                    [impressions] => 70062
                    [words] => купить диван +в москве
                )

            [4] => Array
                (
                    [impressions] => 45905
                    [words] => купить диван кровать
                )

            [5] => Array
                (
                    [impressions] => 45824
                    [words] => купить диван +в спб
                )

            [6] => Array
                (
                    [impressions] => 26833
                    [words] => купить диван +от производителя
                )

            [7] => Array
                (
                    [impressions] => 25078
                    [words] => купить диван недорого +в москве
                )

            [8] => Array
                (
                    [impressions] => 23786
                    [words] => купить диван дешево
                )

            [9] => Array
                (
                    [impressions] => 21915
                    [words] => купить диван +в магазине
                )

            [10] => Array
                (
                    [impressions] => 20965
                    [words] => где купить диван
                )

            [11] => Array
                (
                    [impressions] => 20343
                    [words] => купить диван распродажа
                )

            [12] => Array
                (
                    [impressions] => 20336
                    [words] => купить диван бу
                )

            [13] => Array
                (
                    [impressions] => 19286
                    [words] => купить чехол +на диван
                )

            [14] => Array
                (
                    [impressions] => 18754
                    [words] => купить диван цена
                )

            [15] => Array
                (
                    [impressions] => 17924
                    [words] => купить диван +в интернете
                )

            [16] => Array
                (
                    [impressions] => 16862
                    [words] => диван купить +в интернет магазине
                )

            [17] => Array
                (
                    [impressions] => 16817
                    [words] => купить диван +на авито
                )

            [18] => Array
                (
                    [impressions] => 16603
                    [words] => купить диван аккордеон
                )

            [19] => Array
                (
                    [impressions] => 16347
                    [words] => кресло диван купить
                )

            [20] => Array
                (
                    [impressions] => 16131
                    [words] => купить диван +от производителя недорого
                )

            [21] => Array
                (
                    [impressions] => 15518
                    [words] => купить диван б +у
                )

            [22] => Array
                (
                    [impressions] => 14161
                    [words] => купить диван недорого распродажа
                )

            [23] => Array
                (
                    [impressions] => 12859
                    [words] => купить диван со
                )

            [24] => Array
                (
                    [impressions] => 11796
                    [words] => купить диван +в спб недорого
                )

            [25] => Array
                (
                    [impressions] => 11736
                    [words] => купить детский диван
                )

            [26] => Array
                (
                    [impressions] => 11686
                    [words] => купить угловой диван недорого
                )

            [27] => Array
                (
                    [impressions] => 11598
                    [words] => купить спальный диван
                )

            [28] => Array
                (
                    [impressions] => 11365
                    [words] => купить диван +в минске
                )

            [29] => Array
                (
                    [impressions] => 11168
                    [words] => купить кожаный диван
                )

            [30] => Array
                (
                    [impressions] => 10760
                    [words] => купить диван +в екатеринбурге
                )

            [31] => Array
                (
                    [impressions] => 10721
                    [words] => диван книжка купить
                )

            [32] => Array
                (
                    [impressions] => 10403
                    [words] => купить диван спальным местом
                )

            [33] => Array
                (
                    [impressions] => 10176
                    [words] => купить диван еврокнижка
                )

            [34] => Array
                (
                    [impressions] => 9771
                    [words] => мебель купить диван
                )

            [35] => Array
                (
                    [impressions] => 9456
                    [words] => купить диван трансформер
                )

            [36] => Array
                (
                    [impressions] => 9247
                    [words] => купить диван +в москве распродажа
                )

            [37] => Array
                (
                    [impressions] => 8978
                    [words] => купить диван +на кухню
                )

            [38] => Array
                (
                    [impressions] => 8954
                    [words] => купить диван +с доставкой
                )

            [39] => Array
                (
                    [impressions] => 8781
                    [words] => диван недорого купить +в магазине
                )

            [40] => Array
                (
                    [impressions] => 8726
                    [words] => купить ортопедический диван
                )

            [41] => Array
                (
                    [impressions] => 8708
                    [words] => купить диван со спальным местом
                )

            [42] => Array
                (
                    [impressions] => 8386
                    [words] => купить раскладной диван
                )

            [43] => Array
                (
                    [impressions] => 8214
                    [words] => купить прямой диван
                )

            [44] => Array
                (
                    [impressions] => 8160
                    [words] => купить диван распродажа производителя
                )

            [45] => Array
                (
                    [impressions] => 8152
                    [words] => купить диван +в спб +от производителя
                )

            [46] => Array
                (
                    [impressions] => 8129
                    [words] => купить диван недорого +в интернет
                )

            [47] => Array
                (
                    [impressions] => 8002
                    [words] => купить диван +в интернет магазине недорого
                )

            [48] => Array
                (
                    [impressions] => 7845
                    [words] => купить угловой диван +в москве
                )

            [49] => Array
                (
                    [impressions] => 7814
                    [words] => купить кухонный диван
                )

        )

    [phrasesAssociations] => Array
        (
            [0] => Array
                (
                    [impressions] => 166393
                    [words] => недорогой диван
                )

            [1] => Array
                (
                    [impressions] => 334155
                    [words] => угловой диван
                )

            [2] => Array
                (
                    [impressions] => 50658
                    [words] => дешевый диван
                )

            [3] => Array
                (
                    [impressions] => 5583
                    [words] => диван hoff
                )

            [4] => Array
                (
                    [impressions] => 38596
                    [words] => диван еврокнижка
                )

            [5] => Array
                (
                    [impressions] => 70738
                    [words] => диван распродажа
                )

            [6] => Array
                (
                    [impressions] => 261011
                    [words] => мягкий мебель
                )

            [7] => Array
                (
                    [impressions] => 41784
                    [words] => диван прямой
                )

            [8] => Array
                (
                    [impressions] => 170507
                    [words] => диван цена
                )

            [9] => Array
                (
                    [impressions] => 61144
                    [words] => диван аккордеон
                )

            [10] => Array
                (
                    [impressions] => 824487
                    [words] => шкаф купе
                )

            [11] => Array
                (
                    [impressions] => 55671
                    [words] => диван производитель
                )

            [12] => Array
                (
                    [impressions] => 650078
                    [words] => купить кровать
                )

            [13] => Array
                (
                    [impressions] => 186016
                    [words] => диван кровать
                )

            [14] => Array
                (
                    [impressions] => 474379
                    [words] => много мебель
                )

            [15] => Array
                (
                    [impressions] => 19722
                    [words] => недорогой угловой диван
                )

            [16] => Array
                (
                    [impressions] => 134886
                    [words] => диван москва
                )

            [17] => Array
                (
                    [impressions] => 37230
                    [words] => диван книжка
                )

            [18] => Array
                (
                    [impressions] => 140283
                    [words] => диван каталог
                )

            [19] => Array
                (
                    [impressions] => 141
                    [words] => угловой ортопедический диван кровать
                )

        )

    [lastUpdate] => 1531094400
    [nextPageExists] => 1
)

```

