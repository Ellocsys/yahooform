<?php

namespace Dim\TodoBundle\Services;

// Сервис для работы с yahooApi

class YahooApi
{
    // Метод для получения данных для графика для оной компании(Для нескольких сразу не нашел способа)
    // Принимает обьект Stoke и период
    // Возвращает jsonp ответ

    public function getChartData($stoke = 'goog', $period = '2y')
    {

        //Формируем данные для запроса

        //Формируем запрос

        $symbol = $stoke->getName();

        $url = "chartapi.finance.yahoo.com/instrument/1.0/$symbol/chartdata;type=quote;range=$period/json";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $output = curl_exec($ch);

        curl_close($ch);

        return $output;
    }

    //Получение данных по всем акциям пользователя
    //Принимает массив обьектов stoke и период
    //Возвращает массив массивов

    public function getPortfolioPrice(array $symbol, $period = '2y')
    {
        $outer = [];

        foreach ($symbol as $key => $value) {

            //получаем данные по акции

        $jsonp = $this->getChartData($value);

            //обрезаем обкертку jsonp

        $jsonp = substr($jsonp, strpos($jsonp, '('));

            $json_string = json_decode(trim($jsonp, '();'), true);

            //если акции нет в базе(тогда ответ null) пропускаем и переходим к следующей

            if ($json_string == null) {
                continue;
            }

            //Если это первый проход,то инициализируем элементы массива, если нет, то просто прибвляем к имеющимся
            // count - колличество акции этого типа
            $count = $value->getCount();

            if (empty($outer)) {
                foreach ($json_string['series'] as $key => $item) {
                    $outer[$key]['Date'] = $item['Date'];
                    $outer[$key]['close'] = $item['close'] * $count;
                    $outer[$key]['high'] = $item['high'] * $count;
                    $outer[$key]['low'] = $item['low'] * $count;
                    $outer[$key]['open'] = $item['open'] * $count;
                    $outer[$key]['volume'] = $item['volume'] * $count;
                }
            } else {
                foreach ($json_string['series'] as $key => $item) {
                    $outer[$key]['Date'] = $item['Date'];
                    $outer[$key]['close'] += $item['close'] * $count;
                    $outer[$key]['high'] += $item['high'] * $count;
                    $outer[$key]['low'] += $item['low'] * $count;
                    $outer[$key]['open'] += $item['open'] * $count;
                    $outer[$key]['volume'] += $item['volume'] * $count;
                }
            }
        }

        return $outer;
    }
}
