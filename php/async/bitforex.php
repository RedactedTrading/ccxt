<?php

namespace ccxt\async;

// PLEASE DO NOT EDIT THIS FILE, IT IS GENERATED AND WILL BE OVERWRITTEN:
// https://github.com/ccxt/ccxt/blob/master/CONTRIBUTING.md#how-to-contribute-code

use Exception; // a common import
use ccxt\async\abstract\bitforex as Exchange;
use ccxt\ExchangeError;
use ccxt\ArgumentsRequired;
use React\Async;

class bitforex extends Exchange {

    public function describe() {
        return $this->deep_extend(parent::describe(), array(
            'id' => 'bitforex',
            'name' => 'Bitforex',
            'countries' => array( 'CN' ),
            'rateLimit' => 500, // https://github.com/ccxt/ccxt/issues/5054
            'version' => 'v1',
            'has' => array(
                'CORS' => null,
                'spot' => true,
                'margin' => false,
                'swap' => null, // has but unimplemented
                'future' => false,
                'option' => false,
                'cancelOrder' => true,
                'createOrder' => true,
                'createStopLimitOrder' => false,
                'createStopMarketOrder' => false,
                'createStopOrder' => false,
                'fetchBalance' => true,
                'fetchBorrowRate' => false,
                'fetchBorrowRateHistories' => false,
                'fetchBorrowRateHistory' => false,
                'fetchBorrowRates' => false,
                'fetchBorrowRatesPerSymbol' => false,
                'fetchClosedOrders' => true,
                'fetchMarginMode' => false,
                'fetchMarkets' => true,
                'fetchMyTrades' => true,
                'fetchOHLCV' => true,
                'fetchOpenOrders' => true,
                'fetchOrder' => true,
                'fetchOrderBook' => true,
                'fetchPositionMode' => false,
                'fetchTicker' => true,
                'fetchTickers' => false,
                'fetchTrades' => true,
                'fetchTransactionFees' => false,
                'fetchTransfer' => false,
                'fetchTransfers' => false,
                'fetchWithdrawal' => false,
                'fetchWithdrawals' => false,
                'transfer' => false,
                'withdraw' => false,
            ),
            'timeframes' => array(
                '1m' => '1min',
                '5m' => '5min',
                '15m' => '15min',
                '30m' => '30min',
                '1h' => '1hour',
                '2h' => '2hour',
                '4h' => '4hour',
                '12h' => '12hour',
                '1d' => '1day',
                '1w' => '1week',
                '1M' => '1month',
            ),
            'urls' => array(
                'logo' => 'https://user-images.githubusercontent.com/51840849/87295553-1160ec00-c50e-11ea-8ea0-df79276a9646.jpg',
                'api' => array(
                    'rest' => 'https://api.bitforex.com',
                ),
                'www' => 'https://www.bitforex.com',
                'doc' => 'https://github.com/githubdev2020/API_Doc_en/wiki',
                'fees' => 'https://help.bitforex.com/en_us/?cat=13',
                'referral' => 'https://www.bitforex.com/en/invitationRegister?inviterId=1867438',
            ),
            'api' => array(
                'public' => array(
                    'get' => array(
                        '/api/v1/ping' => 0.2,
                        '/api/v1/time' => 0.2,
                        'api/v1/market/symbols' => 20,
                        'api/v1/market/ticker' => 4,
                        'api/v1/market/ticker-all' => 4,
                        'api/v1/market/depth' => 4,
                        'api/v1/market/depth-all' => 4,
                        'api/v1/market/trades' => 20,
                        'api/v1/market/kline' => 20,
                    ),
                ),
                'private' => array(
                    'post' => array(
                        'api/v1/fund/mainAccount' => 1,
                        'api/v1/fund/allAccount' => 30,
                        'api/v1/trade/placeOrder' => 1,
                        'api/v1/trade/placeMultiOrder' => 10,
                        'api/v1/trade/cancelOrder' => 1,
                        'api/v1/trade/cancelMultiOrder' => 6.67,
                        'api/v1/trade/cancelAllOrder' => 20,
                        'api/v1/trade/orderInfo' => 1,
                        'api/v1/trade/multiOrderInfo' => 10,
                        'api/v1/trade/orderInfos' => 20,
                        'api/v1/trade/myTrades' => 2,
                    ),
                ),
            ),
            'fees' => array(
                'trading' => array(
                    'tierBased' => false,
                    'percentage' => true,
                    'maker' => $this->parse_number('0.001'),
                    'taker' => $this->parse_number('0.001'),
                ),
                'funding' => array(
                    'tierBased' => false,
                    'percentage' => true,
                    'deposit' => array(),
                    'withdraw' => array(),
                ),
            ),
            'commonCurrencies' => array(
                'BKC' => 'Bank Coin',
                'CAPP' => 'Crypto Application Token',
                'CREDIT' => 'TerraCredit',
                'CTC' => 'Culture Ticket Chain',
                'EWT' => 'EcoWatt Token',
                'IQ' => 'IQ.Cash',
                'MIR' => 'MIR COIN',
                'NOIA' => 'METANOIA',
                'TON' => 'To The Moon',
            ),
            'precisionMode' => TICK_SIZE,
            'exceptions' => array(
                '1000' => '\\ccxt\\OrderNotFound', // array("code":"1000","success":false,"time":1643047898676,"message":"The order does not exist or the status is wrong")
                '1003' => '\\ccxt\\BadSymbol', // array("success":false,"code":"1003","message":"Param Invalid:param invalid -symbol:symbol error")
                '1013' => '\\ccxt\\AuthenticationError',
                '1016' => '\\ccxt\\AuthenticationError',
                '1017' => '\\ccxt\\PermissionDenied', // array("code":"1017","success":false,"time":1602670594367,"message":"IP not allow")
                '1019' => '\\ccxt\\BadSymbol', // array("code":"1019","success":false,"time":1607087743778,"message":"Symbol Invalid")
                '3002' => '\\ccxt\\InsufficientFunds',
                '4002' => '\\ccxt\\InvalidOrder', // array("success":false,"code":"4002","message":"Price unreasonable")
                '4003' => '\\ccxt\\InvalidOrder', // array("success":false,"code":"4003","message":"amount too small")
                '4004' => '\\ccxt\\OrderNotFound',
                '10204' => '\\ccxt\\DDoSProtection',
            ),
        ));
    }

    public function fetch_markets($params = array ()) {
        return Async\async(function () use ($params) {
            /**
             * retrieves $data on all markets for bitforex
             * @param {array} [$params] extra parameters specific to the exchange api endpoint
             * @return {array[]} an array of objects representing $market $data
             */
            $response = Async\await($this->publicGetApiV1MarketSymbols ($params));
            //
            //    {
            //        "data" => array(
            //            array(
            //                "amountPrecision":4,
            //                "minOrderAmount":3.0E-4,
            //                "pricePrecision":2,
            //                "symbol":"coin-usdt-btc"
            //            ),
            //            ...
            //        )
            //    }
            //
            $data = $response['data'];
            $result = array();
            for ($i = 0; $i < count($data); $i++) {
                $market = $data[$i];
                $id = $this->safe_string($market, 'symbol');
                $symbolParts = explode('-', $id);
                $baseId = $symbolParts[2];
                $quoteId = $symbolParts[1];
                $base = $this->safe_currency_code($baseId);
                $quote = $this->safe_currency_code($quoteId);
                $result[] = array(
                    'id' => $id,
                    'symbol' => $base . '/' . $quote,
                    'base' => $base,
                    'quote' => $quote,
                    'settle' => null,
                    'baseId' => $baseId,
                    'quoteId' => $quoteId,
                    'settleId' => null,
                    'type' => 'spot',
                    'spot' => true,
                    'margin' => false,
                    'swap' => false,
                    'future' => false,
                    'option' => false,
                    'active' => true,
                    'contract' => false,
                    'linear' => null,
                    'inverse' => null,
                    'contractSize' => null,
                    'expiry' => null,
                    'expiryDateTime' => null,
                    'strike' => null,
                    'optionType' => null,
                    'precision' => array(
                        'amount' => $this->parse_number($this->parse_precision($this->safe_string($market, 'amountPrecision'))),
                        'price' => $this->parse_number($this->parse_precision($this->safe_string($market, 'pricePrecision'))),
                    ),
                    'limits' => array(
                        'leverage' => array(
                            'min' => null,
                            'max' => null,
                        ),
                        'amount' => array(
                            'min' => $this->safe_number($market, 'minOrderAmount'),
                            'max' => null,
                        ),
                        'price' => array(
                            'min' => null,
                            'max' => null,
                        ),
                        'cost' => array(
                            'min' => null,
                            'max' => null,
                        ),
                    ),
                    'info' => $market,
                );
            }
            return $result;
        }) ();
    }

    public function parse_trade($trade, $market = null) {
        //
        // fetchTrades (public) v1
        //
        //      {
        //          "price":57594.53,
        //          "amount":0.3172,
        //          "time":1637329685322,
        //          "direction":1,
        //          "tid":"1131019666"
        //      }
        //
        //      {
        //          "price":57591.33,
        //          "amount":0.002,
        //          "time":1637329685322,
        //          "direction":1,
        //          "tid":"1131019639"
        //      }
        //
        // fetchMyTrades (private)
        //
        //     {
        //         "symbol" => "coin-usdt-babydoge",
        //         "tid" => 7289,
        //         "orderId" => "b6fe2b61-e5cb-4970-9bdc-8c7cd1fcb4d8",
        //         "price" => "0.000007",
        //         "amount" => "50000000",
        //         "tradeFee" => "50000",
        //         "tradeFeeCurrency" => "babydoge",
        //         "time" => "1684750536460",
        //         "isBuyer" => true,
        //         "isMaker" => true,
        //         "isSelfTrade" => true
        //     }
        //
        $marketId = $this->safe_string($trade, 'symbol');
        $market = $this->safe_market($marketId, $market);
        $timestamp = $this->safe_integer($trade, 'time');
        $id = $this->safe_string($trade, 'tid');
        $orderId = $this->safe_string($trade, 'orderId');
        $priceString = $this->safe_string($trade, 'price');
        $amountString = $this->safe_string($trade, 'amount');
        $sideId = $this->safe_integer($trade, 'direction');
        $side = $this->parse_side($sideId);
        if ($side === null) {
            $isBuyer = $this->safe_value($trade, 'isBuyer');
            $side = $isBuyer ? 'buy' : 'sell';
        }
        $takerOrMaker = null;
        $isMaker = $this->safe_value($trade, 'isMaker');
        if ($isMaker !== null) {
            $takerOrMaker = ($isMaker) ? 'maker' : 'taker';
        }
        $fee = null;
        $feeCostString = $this->safe_string($trade, 'tradeFee');
        if ($feeCostString !== null) {
            $feeCurrencyId = $this->safe_string($trade, 'tradeFeeCurrency');
            $feeCurrencyCode = $this->safe_currency_code($feeCurrencyId);
            $fee = array(
                'cost' => $feeCostString,
                'currency' => $feeCurrencyCode,
            );
        }
        return $this->safe_trade(array(
            'info' => $trade,
            'id' => $id,
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601($timestamp),
            'symbol' => $market['symbol'],
            'type' => null,
            'side' => $side,
            'price' => $priceString,
            'amount' => $amountString,
            'cost' => null,
            'order' => $orderId,
            'fee' => $fee,
            'takerOrMaker' => $takerOrMaker,
        ), $market);
    }

    public function fetch_trades(string $symbol, ?int $since = null, ?int $limit = null, $params = array ()) {
        return Async\async(function () use ($symbol, $since, $limit, $params) {
            /**
             * get the list of most recent trades for a particular $symbol
             * @param {string} $symbol unified $symbol of the $market to fetch trades for
             * @param {int} [$since] timestamp in ms of the earliest trade to fetch
             * @param {int} [$limit] the maximum amount of trades to fetch
             * @param {array} [$params] extra parameters specific to the bitforex api endpoint
             * @return {Trade[]} a list of ~@link https://docs.ccxt.com/en/latest/manual.html?#public-trades trade structures~
             */
            Async\await($this->load_markets());
            $request = array(
                'symbol' => $this->market_id($symbol),
            );
            if ($limit !== null) {
                $request['size'] = $limit;
            }
            $market = $this->market($symbol);
            $response = Async\await($this->publicGetApiV1MarketTrades (array_merge($request, $params)));
            //
            // {
            //  "data":
            //      array(
            //          {
            //              "price":57594.53,
            //              "amount":0.3172,
            //              "time":1637329685322,
            //              "direction":1,
            //              "tid":"1131019666"
            //          }
            //      ),
            //  "success" => true,
            //  "time" => 1637329688475
            // }
            //
            return $this->parse_trades($response['data'], $market, $since, $limit);
        }) ();
    }

    public function fetch_my_trades(?string $symbol = null, ?int $since = null, ?int $limit = null, $params = array ()) {
        return Async\async(function () use ($symbol, $since, $limit, $params) {
            /**
             * fetch all trades made by the user
             * @see https://apidoc.bitforex.com/#spot-account-trade
             * @param {string} $symbol unified $market $symbol
             * @param {int} [$since] the earliest time in ms to fetch trades for
             * @param {int} [$limit] the maximum number of trades structures to retrieve
             * @param {array} [$params] extra parameters specific to the bitforex api endpoint
             * @return {Trade[]} a list of ~@link https://docs.ccxt.com/#/?id=trade-structure trade structures~
             */
            $this->check_required_symbol('fetchMyTrades', $symbol);
            Async\await($this->load_markets());
            $request = array(
                // 'symbol' => $market['id'],
                // 'orderId' => orderId,
                // 'startTime' => timestamp,
                // 'endTime' => timestamp,
                // 'limit' => $limit, // default 500, max 1000
            );
            $market = $this->market($symbol);
            $request['symbol'] = $market['id'];
            if ($limit !== null) {
                $request['limit'] = $limit;
            }
            if ($since !== null) {
                $request['startTime'] = max ($since - 1, 0);
            }
            $endTime = $this->safe_integer_2($params, 'until', 'endTime');
            if ($endTime !== null) {
                $request['endTime'] = $endTime;
            }
            $params = $this->omit($params, array( 'until' ));
            $response = Async\await($this->privatePostApiV1TradeMyTrades (array_merge($request, $params)));
            //
            //     {
            //         "data" => array(
            //             {
            //                 "symbol" => "coin-usdt-babydoge",
            //                 "tid" => 7289,
            //                 "orderId" => "a262d030-11a5-40fd-a07c-7ba84aa68752",
            //                 "price" => "0.000007",
            //                 "amount" => "50000000",
            //                 "tradeFee" => "0.35",
            //                 "tradeFeeCurrency" => "usdt",
            //                 "time" => "1684750536460",
            //                 "isBuyer" => false,
            //                 "isMaker" => false,
            //                 "isSelfTrade" => true
            //             }
            //         ),
            //         "success" => true,
            //         "time" => 1685009320042
            //     }
            //
            $data = $this->safe_value($response, 'data', array());
            return $this->parse_trades($data, $market, $since, $limit);
        }) ();
    }

    public function parse_balance($response) {
        $data = $response['data'];
        $result = array( 'info' => $response );
        for ($i = 0; $i < count($data); $i++) {
            $balance = $data[$i];
            $currencyId = $this->safe_string($balance, 'currency');
            $code = $this->safe_currency_code($currencyId);
            $account = $this->account();
            $account['used'] = $this->safe_string($balance, 'frozen');
            $account['free'] = $this->safe_string($balance, 'active');
            $account['total'] = $this->safe_string($balance, 'fix');
            $result[$code] = $account;
        }
        return $this->safe_balance($result);
    }

    public function fetch_balance($params = array ()) {
        return Async\async(function () use ($params) {
            /**
             * query for balance and get the amount of funds available for trading or funds locked in orders
             * @param {array} [$params] extra parameters specific to the bitforex api endpoint
             * @return {array} a ~@link https://docs.ccxt.com/en/latest/manual.html?#balance-structure balance structure~
             */
            Async\await($this->load_markets());
            $response = Async\await($this->privatePostApiV1FundAllAccount ($params));
            return $this->parse_balance($response);
        }) ();
    }

    public function parse_ticker($ticker, $market = null) {
        //
        //     {
        //         "buy":7.04E-7,
        //         "date":1643371198598,
        //         "high":7.48E-7,
        //         "last":7.28E-7,
        //         "low":7.10E-7,
        //         "sell":7.54E-7,
        //         "vol":9877287.2874
        //     }
        //
        $symbol = $this->safe_symbol(null, $market);
        $timestamp = $this->safe_integer($ticker, 'date');
        return $this->safe_ticker(array(
            'symbol' => $symbol,
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601($timestamp),
            'high' => $this->safe_string($ticker, 'high'),
            'low' => $this->safe_string($ticker, 'low'),
            'bid' => $this->safe_string($ticker, 'buy'),
            'bidVolume' => null,
            'ask' => $this->safe_string($ticker, 'sell'),
            'askVolume' => null,
            'vwap' => null,
            'open' => null,
            'close' => $this->safe_string($ticker, 'last'),
            'last' => $this->safe_string($ticker, 'last'),
            'previousClose' => null,
            'change' => null,
            'percentage' => null,
            'average' => null,
            'baseVolume' => $this->safe_string($ticker, 'vol'),
            'quoteVolume' => null,
            'info' => $ticker,
        ), $market);
    }

    public function fetch_ticker(string $symbol, $params = array ()) {
        return Async\async(function () use ($symbol, $params) {
            /**
             * fetches a price $ticker, a statistical calculation with the information calculated over the past 24 hours for a specific $market
             * @param {string} $symbol unified $symbol of the $market to fetch the $ticker for
             * @param {array} [$params] extra parameters specific to the bitforex api endpoint
             * @return {array} a ~@link https://docs.ccxt.com/#/?id=$ticker-structure $ticker structure~
             */
            Async\await($this->load_markets());
            $market = $this->markets[$symbol];
            $request = array(
                'symbol' => $market['id'],
            );
            $response = Async\await($this->publicGetApiV1MarketTickerAll (array_merge($request, $params)));
            $ticker = $this->safe_value($response, 'data');
            //
            //     {
            //         "data":array(
            //             "buy":37082.83,
            //             "date":1643388686660,
            //             "high":37487.83,
            //             "last":37086.79,
            //             "low":35544.44,
            //             "sell":37090.52,
            //             "vol":690.9776
            //         ),
            //         "success":true,
            //         "time":1643388686660
            //     }
            //
            return $this->parse_ticker($ticker, $market);
        }) ();
    }

    public function parse_ohlcv($ohlcv, $market = null) {
        //
        //     {
        //         "close":0.02505143,
        //         "currencyVol":0,
        //         "high":0.02506422,
        //         "low":0.02505143,
        //         "open":0.02506095,
        //         "time":1591508940000,
        //         "vol":51.1869
        //     }
        //
        return array(
            $this->safe_integer($ohlcv, 'time'),
            $this->safe_number($ohlcv, 'open'),
            $this->safe_number($ohlcv, 'high'),
            $this->safe_number($ohlcv, 'low'),
            $this->safe_number($ohlcv, 'close'),
            $this->safe_number($ohlcv, 'vol'),
        );
    }

    public function fetch_ohlcv(string $symbol, $timeframe = '1m', ?int $since = null, ?int $limit = null, $params = array ()) {
        return Async\async(function () use ($symbol, $timeframe, $since, $limit, $params) {
            /**
             * fetches historical candlestick $data containing the open, high, low, and close price, and the volume of a $market
             * @param {string} $symbol unified $symbol of the $market to fetch OHLCV $data for
             * @param {string} $timeframe the length of time each candle represents
             * @param {int} [$since] timestamp in ms of the earliest candle to fetch
             * @param {int} [$limit] the maximum amount of candles to fetch
             * @param {array} [$params] extra parameters specific to the bitforex api endpoint
             * @return {int[][]} A list of candles ordered, open, high, low, close, volume
             */
            Async\await($this->load_markets());
            $market = $this->market($symbol);
            $request = array(
                'symbol' => $market['id'],
                'ktype' => $this->safe_string($this->timeframes, $timeframe, $timeframe),
            );
            if ($limit !== null) {
                $request['size'] = $limit; // default 1, max 600
            }
            $response = Async\await($this->publicGetApiV1MarketKline (array_merge($request, $params)));
            //
            //     {
            //         "data":array(
            //             array("close":0.02505143,"currencyVol":0,"high":0.02506422,"low":0.02505143,"open":0.02506095,"time":1591508940000,"vol":51.1869),
            //             array("close":0.02503914,"currencyVol":0,"high":0.02506687,"low":0.02503914,"open":0.02505358,"time":1591509000000,"vol":9.1082),
            //             array("close":0.02505172,"currencyVol":0,"high":0.02507466,"low":0.02503895,"open":0.02506371,"time":1591509060000,"vol":63.7431),
            //         ),
            //         "success":true,
            //         "time":1591509427131
            //     }
            //
            $data = $this->safe_value($response, 'data', array());
            return $this->parse_ohlcvs($data, $market, $timeframe, $since, $limit);
        }) ();
    }

    public function fetch_order_book(string $symbol, ?int $limit = null, $params = array ()) {
        return Async\async(function () use ($symbol, $limit, $params) {
            /**
             * fetches information on open orders with bid (buy) and ask (sell) prices, volumes and other $data
             * @param {string} $symbol unified $symbol of the $market to fetch the order book for
             * @param {int} [$limit] the maximum amount of order book entries to return
             * @param {array} [$params] extra parameters specific to the bitforex api endpoint
             * @return {array} A dictionary of ~@link https://docs.ccxt.com/#/?id=order-book-structure order book structures~ indexed by $market symbols
             */
            Async\await($this->load_markets());
            $market = $this->market($symbol);
            $request = array(
                'symbol' => $market['id'],
            );
            if ($limit !== null) {
                $request['size'] = $limit;
            }
            $response = Async\await($this->publicGetApiV1MarketDepthAll (array_merge($request, $params)));
            $data = $this->safe_value($response, 'data');
            $timestamp = $this->safe_integer($response, 'time');
            return $this->parse_order_book($data, $market['symbol'], $timestamp, 'bids', 'asks', 'price', 'amount');
        }) ();
    }

    public function parse_order_status($status) {
        $statuses = array(
            '0' => 'open',
            '1' => 'open',
            '2' => 'closed',
            '3' => 'canceled',
            '4' => 'canceled',
        );
        return (is_array($statuses) && array_key_exists($status, $statuses)) ? $statuses[$status] : $status;
    }

    public function parse_side($sideId) {
        if ($sideId === 1) {
            return 'buy';
        } elseif ($sideId === 2) {
            return 'sell';
        } else {
            return null;
        }
    }

    public function parse_order($order, $market = null) {
        $id = $this->safe_string($order, 'orderId');
        $timestamp = $this->safe_number($order, 'createTime');
        $lastTradeTimestamp = $this->safe_number($order, 'lastTime');
        $symbol = $market['symbol'];
        $sideId = $this->safe_integer($order, 'tradeType');
        $side = $this->parse_side($sideId);
        $type = null;
        $price = $this->safe_string($order, 'orderPrice');
        $average = $this->safe_string($order, 'avgPrice');
        $amount = $this->safe_string($order, 'orderAmount');
        $filled = $this->safe_string($order, 'dealAmount');
        $status = $this->parse_order_status($this->safe_string($order, 'orderState'));
        $feeSide = ($side === 'buy') ? 'base' : 'quote';
        $feeCurrency = $market[$feeSide];
        $fee = array(
            'cost' => $this->safe_number($order, 'tradeFee'),
            'currency' => $feeCurrency,
        );
        return $this->safe_order(array(
            'info' => $order,
            'id' => $id,
            'clientOrderId' => null,
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601($timestamp),
            'lastTradeTimestamp' => $lastTradeTimestamp,
            'symbol' => $symbol,
            'type' => $type,
            'timeInForce' => null,
            'postOnly' => null,
            'side' => $side,
            'price' => $price,
            'stopPrice' => null,
            'triggerPrice' => null,
            'cost' => null,
            'average' => $average,
            'amount' => $amount,
            'filled' => $filled,
            'remaining' => null,
            'status' => $status,
            'fee' => $fee,
            'trades' => null,
        ), $market);
    }

    public function fetch_order(string $id, ?string $symbol = null, $params = array ()) {
        return Async\async(function () use ($id, $symbol, $params) {
            /**
             * fetches information on an $order made by the user
             * @param {string} $symbol unified $symbol of the $market the $order was made in
             * @param {array} [$params] extra parameters specific to the bitforex api endpoint
             * @return {array} An ~@link https://docs.ccxt.com/#/?$id=$order-structure $order structure~
             */
            Async\await($this->load_markets());
            $market = $this->market($symbol);
            $request = array(
                'symbol' => $this->market_id($symbol),
                'orderId' => $id,
            );
            $response = Async\await($this->privatePostApiV1TradeOrderInfo (array_merge($request, $params)));
            $order = $this->parse_order($response['data'], $market);
            return $order;
        }) ();
    }

    public function fetch_open_orders(?string $symbol = null, ?int $since = null, ?int $limit = null, $params = array ()) {
        return Async\async(function () use ($symbol, $since, $limit, $params) {
            /**
             * fetch all unfilled currently open orders
             * @param {string} $symbol unified $market $symbol
             * @param {int} [$since] the earliest time in ms to fetch open orders for
             * @param {int} [$limit] the maximum number of  open orders structures to retrieve
             * @param {array} [$params] extra parameters specific to the bitforex api endpoint
             * @return {Order[]} a list of ~@link https://docs.ccxt.com/#/?id=order-structure order structures~
             */
            if ($symbol === null) {
                throw new ArgumentsRequired($this->id . ' fetchMyTrades() requires a $symbol argument');
            }
            Async\await($this->load_markets());
            $market = $this->market($symbol);
            $request = array(
                'symbol' => $this->market_id($symbol),
                'state' => 0,
            );
            $response = Async\await($this->privatePostApiV1TradeOrderInfos (array_merge($request, $params)));
            return $this->parse_orders($response['data'], $market, $since, $limit);
        }) ();
    }

    public function fetch_closed_orders(?string $symbol = null, ?int $since = null, ?int $limit = null, $params = array ()) {
        return Async\async(function () use ($symbol, $since, $limit, $params) {
            /**
             * fetches information on multiple closed orders made by the user
             * @param {string} $symbol unified $market $symbol of the $market orders were made in
             * @param {int} [$since] the earliest time in ms to fetch orders for
             * @param {int} [$limit] the maximum number of  orde structures to retrieve
             * @param {array} [$params] extra parameters specific to the bitforex api endpoint
             * @return {Order[]} a list of ~@link https://docs.ccxt.com/#/?id=order-structure order structures~
             */
            if ($symbol === null) {
                throw new ArgumentsRequired($this->id . ' fetchMyTrades() requires a $symbol argument');
            }
            Async\await($this->load_markets());
            $market = $this->market($symbol);
            $request = array(
                'symbol' => $this->market_id($symbol),
                'state' => 1,
            );
            $response = Async\await($this->privatePostApiV1TradeOrderInfos (array_merge($request, $params)));
            return $this->parse_orders($response['data'], $market, $since, $limit);
        }) ();
    }

    public function create_order(string $symbol, string $type, string $side, $amount, $price = null, $params = array ()) {
        return Async\async(function () use ($symbol, $type, $side, $amount, $price, $params) {
            /**
             * create a trade order
             * @param {string} $symbol unified $symbol of the $market to create an order in
             * @param {string} $type 'market' or 'limit'
             * @param {string} $side 'buy' or 'sell'
             * @param {float} $amount how much of currency you want to trade in units of base currency
             * @param {float} $price the $price at which the order is to be fullfilled, in units of the quote currency, ignored in $market orders
             * @param {array} [$params] extra parameters specific to the bitforex api endpoint
             * @return {array} an ~@link https://docs.ccxt.com/#/?id=order-structure order structure~
             */
            Async\await($this->load_markets());
            $sideId = null;
            if ($side === 'buy') {
                $sideId = 1;
            } elseif ($side === 'sell') {
                $sideId = 2;
            }
            $market = $this->market($symbol);
            $request = array(
                'symbol' => $market['id'],
                'price' => $price,
                'amount' => $amount,
                'tradeType' => $sideId,
            );
            $response = Async\await($this->privatePostApiV1TradePlaceOrder (array_merge($request, $params)));
            $data = $response['data'];
            return $this->safe_order(array(
                'info' => $response,
                'id' => $this->safe_string($data, 'orderId'),
            ), $market);
        }) ();
    }

    public function cancel_order(string $id, ?string $symbol = null, $params = array ()) {
        return Async\async(function () use ($id, $symbol, $params) {
            /**
             * cancels an open order
             * @param {string} $id order $id
             * @param {string} $symbol unified $symbol of the market the order was made in
             * @param {array} [$params] extra parameters specific to the bitforex api endpoint
             * @return {array} An ~@link https://docs.ccxt.com/#/?$id=order-structure order structure~
             */
            Async\await($this->load_markets());
            $request = array(
                'orderId' => $id,
            );
            if ($symbol !== null) {
                $request['symbol'] = $this->market_id($symbol);
            }
            $results = Async\await($this->privatePostApiV1TradeCancelOrder (array_merge($request, $params)));
            $success = $results['success'];
            $returnVal = array( 'info' => $results, 'success' => $success );
            return $returnVal;
        }) ();
    }

    public function sign($path, $api = 'public', $method = 'GET', $params = array (), $headers = null, $body = null) {
        $url = $this->urls['api']['rest'] . '/' . $this->implode_params($path, $params);
        $query = $this->omit($params, $this->extract_params($path));
        if ($api === 'public') {
            if ($query) {
                $url .= '?' . $this->urlencode($query);
            }
        } else {
            $this->check_required_credentials();
            $payload = $this->urlencode(array( 'accessKey' => $this->apiKey ));
            $query['nonce'] = $this->milliseconds();
            if ($query) {
                $payload .= '&' . $this->urlencode($this->keysort($query));
            }
            // $message = '/' . 'api/' . $this->version . '/' . $path . '?' . $payload;
            $message = '/' . $path . '?' . $payload;
            $signature = $this->hmac($this->encode($message), $this->encode($this->secret), 'sha256');
            $body = $payload . '&signData=' . $signature;
            $headers = array(
                'Content-Type' => 'application/x-www-form-urlencoded',
            );
        }
        return array( 'url' => $url, 'method' => $method, 'body' => $body, 'headers' => $headers );
    }

    public function handle_errors($code, $reason, $url, $method, $headers, $body, $response, $requestHeaders, $requestBody) {
        if (gettype($body) !== 'string') {
            return null; // fallback to default error handler
        }
        if (($body[0] === '{') || ($body[0] === '[')) {
            $feedback = $this->id . ' ' . $body;
            $success = $this->safe_value($response, 'success');
            if ($success !== null) {
                if (!$success) {
                    $codeInner = $this->safe_string($response, 'code');
                    $this->throw_exactly_matched_exception($this->exceptions, $codeInner, $feedback);
                    throw new ExchangeError($feedback);
                }
            }
        }
        return null;
    }
}