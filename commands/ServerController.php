<?php
namespace app\commands;
use app\models\form\StatisticsForm;
use app\models\Settings;
use Workerman\Worker;
use Workerman\Timer;
use yii\httpclient\Client;

use yii\console\Controller;
use function PHPUnit\Framework\arrayHasKey;

class ServerController extends Controller
{
    public $info = 'buy';
    public function actionStart() {
        $ws_worker = new Worker('websocket://test.sacura.info:61111');

        // 4 processes
        $ws_worker->count = 1;
        $ws_worker->onWorkerStart = function ($task) {
            // 2.5 seconds
            $time_interval = 15;

            $day = date('d');
            $timer_id = Timer::add($time_interval, function () use ($day, $task) {
                $price = 0;
                $btc = 0;
                $usdt = 0;
                $byCount = 0;
                $sellCount = 0;
                $sell = array();
                $by = array();
                $origClientBy = array();
                $origIdBy = array();
                $origClientSell = array();
                $origIdSell = array();
                $countries = Settings::find()->orderBy(['id' => SORT_DESC])->all();
                if(isset($countries[0]['dis']) && $countries[0]['dis'] == 0) {
                    $client = new Client();
                    $response = $client->createRequest()
                        ->setFormat(Client::FORMAT_JSON)
                        ->setMethod('get')
                        ->setUrl('https://api.commex.com/api/v1/ticker/price')
                        ->setHeaders(['Content-Type' => 'application/json'])
                        ->send();

                    foreach ($response->data as $key => $value) {
                        if ($value['symbol'] == 'BTCUSDT') {
                            $price = $value["price"];
                        }
                    }

                    $stringAccount = "RecvWindow=60000&timestamp=" . time() . '000';
                    $hmacDigestAccount = hash_hmac('sha256', $stringAccount, $countries[0]['secret']);
                    $urlAccount = "https://api.commex.com/api/v1/account?" . $stringAccount . "&signature=" . $hmacDigestAccount;
                    $account = $client->createRequest()
                        ->setFormat(Client::FORMAT_JSON)
                        ->setMethod('get')
                        ->setUrl($urlAccount)
                        ->setHeaders(['Content-Type' => 'application/json', "X-MBX-APIKEY" => $countries[0]['api']])
                        ->send();
                    if(isset($account->data["balances"])) {
                        foreach ($account->data["balances"] as $key => $value) {
                            if ($value['asset'] == 'BTC') {
                                $btc = $value["free"];
                            }
                            if ($value['asset'] == 'USDT') {
                                $usdt = $value["free"];
                            }
                        }
                    }
                    $string = "symbol=BTCUSDT&RecvWindow=60000&timestamp=" . time() . '000';
                    $hmacDigest = hash_hmac('sha256', $string, $countries[0]['secret']);
                    $url = "https://api.commex.com/api/v1/openOrders?" . $string . "&signature=" . $hmacDigest;
                    $order = $client->createRequest()
                        ->setFormat(Client::FORMAT_JSON)
                        ->setMethod('get')
                        ->setUrl($url)
                        ->setHeaders(['Content-Type' => 'application/json', "X-MBX-APIKEY" => $countries[0]['api']])
                        ->send();

                    foreach ($order->data as $key => $value) {
                        if (isset($value["side"]) && $value["side"] == 'BUY') {
                            $by[] = $value["price"];
                            $origClientBy[] = $value["clientOrderId"];
                            $origIdBy[] = $value["orderId"];
                            $byCount += (float)$value["origQty"] * $price;
                        }
                        if (isset($value["side"]) && $value["side"] == 'SELL') {
                            $sell[] = $value["price"];
                            $origClientSell[] = $value["clientOrderId"];
                            $origIdSell[] = $value["orderId"];
                            $byCount += (float)$value["origQty"] * $price;
                        }
                    }
                    foreach ($by as $key => $value) {
                        if ((float)$value < (float)$price - $countries[0]['plecho']) {
                            $string = "symbol=BTCUSDT" . "&orderId=" . $origIdBy[$key] . "&recvWindow=600000" . "&timestamp=" . $times;
                            $hmacDigest = hash_hmac('sha256', $string, $countries[0]['secret']);
                            $url = "https://api.commex.com/api/v1/order?" . $string . "&signature=" . $hmacDigest;
                            $client->createRequest()
                                ->setFormat(Client::FORMAT_JSON)
                                ->setMethod('delete')
                                ->setUrl($url)
                                ->setHeaders(['Content-Type' => 'application/json', "X-MBX-APIKEY" => $countries[0]['api']])
                                ->send();
                        }
                    }
                    foreach ($sell as $key => $value) {
                        if ((float)$value > (float)$price + $countries[0]['plecho']) {
                            $times = time() . '000';
                            $string = "symbol=BTCUSDT" . "&orderId=" . $origIdSell[$key] . "&recvWindow=600000" . "&timestamp=" . $times;
                            $hmacDigest = hash_hmac('sha256', $string, $countries[0]['secret']);
                            $url = "https://api.commex.com/api/v1/order?" . $string . "&signature=" . $hmacDigest;
                            $client->createRequest()
                                ->setFormat(Client::FORMAT_JSON)
                                ->setMethod('delete')
                                ->setUrl($url)
                                ->setHeaders(['Content-Type' => 'application/json', "X-MBX-APIKEY" => $countries[0]['api']])
                                ->send();
                        }
                    }
                    if($this->info == 'sell') {
                        if(count($sell) < $countries[0]['max'] && $btc*$price >= 11) {
                            $in = true;
                            foreach ($sell as $key => $value) {
                                if (abs((float)$value - (float)$countries[0]['intervals'] - $price) <= $countries[0]['new']) {
                                    $in = false;
                                }
                            }
                            if($in) {
                                $times = time() . '000';
                                $string = "symbol=BTCUSDT" . "&side=SELL&type=LIMIT&timeInForce=GTC&quantity=0.00036&price=" . $price + $countries[0]['intervals'] . "&recvWindow=600000" . "&timestamp=" . $times;
                                $hmacDigest = hash_hmac('sha256', $string, $countries[0]['secret']);
                                $url = "https://api.commex.com/api/v1/order?" . $string . "&signature=" . $hmacDigest;
                               $client->createRequest()
                                    ->setFormat(Client::FORMAT_JSON)
                                    ->setMethod('post')
                                    ->setUrl($url)
                                    ->setHeaders(['Content-Type' => 'application/json', "X-MBX-APIKEY" => $countries[0]['api']])
                                    ->send();
                            }
                        }
                    }
                    if($this->info == 'buy') {
                        if(count($by) < $countries[0]['max'] && $usdt >= 11) {
                            $in = true;
                            foreach ($by as $key => $value) {
                                if(abs($price - (float)$value - (float)$countries[0]['intervals'])  <=  $countries[0]['new']) {
                                    $in = false;
                                }
                            }
                            if ($in) {
                                $times = time() . '000';
                                $string = "symbol=BTCUSDT" . "&side=BUY&type=LIMIT&timeInForce=GTC&quantity=0.00036&price=" . $price - $countries[0]['intervals'] . "&recvWindow=600000" . "&timestamp=" . $times;
                                $hmacDigest = hash_hmac('sha256', $string, $countries[0]['secret']);
                                $url = "https://api.commex.com/api/v1/order?" . $string . "&signature=" . $hmacDigest;
                                $client->createRequest()
                                    ->setFormat(Client::FORMAT_JSON)
                                    ->setMethod('post')
                                    ->setUrl($url)
                                    ->setHeaders(['Content-Type' => 'application/json', "X-MBX-APIKEY" => $countries[0]['api']])
                                    ->send();
                            }
                        }

                    }
                    if($this->info == 'buy') {
                        $this->info = 'sell';
                    }else {
                        $this->info = 'buy';
                    }
                }
            });
        };
        // Emitted when new connection come
        $ws_worker->onConnect = function ($connection) {
            $connection->send('ok');
        };

        // Emitted when data received
        $ws_worker->onMessage = function ($connection, $data) {
            // if, server got message from frontend, server send message to Frontend $data
            $connection->send($data);
        };

        // Emitted when connection closed
        $ws_worker->onClose = function ($connection) {
        };
        // Run worker
        Worker::runAll();
    }
    public function actionGo()
    {
        $this->actionStart();
    }
}