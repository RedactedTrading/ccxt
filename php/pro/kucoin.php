<?php

namespace ccxt\pro;

// PLEASE DO NOT EDIT THIS FILE, IT IS GENERATED AND WILL BE OVERWRITTEN:
// https://github.com/ccxt/ccxt/blob/master/CONTRIBUTING.md#how-to-contribute-code

use Exception; // a common import
use ccxt\ExchangeError;
use React\Async;

class kucoin extends \ccxt\async\kucoin {

    public function describe() {
        return $this->deep_extend(parent::describe(), array(
            'has' => array(
                'ws' => true,
                'watchOrderBook' => true,
                'watchOrders' => true,
                'watchMyTrades' => true,
                'watchTickers' => false, // for now
                'watchTicker' => true,
                'watchTrades' => true,
                'watchBalance' => true,
                'watchOHLCV' => true,
            ),
            'options' => array(
                'tradesLimit' => 1000,
                'watchTicker' => array(
                    'name' => 'market/snapshot', // market/ticker
                ),
                'watchOrderBook' => array(
                    'snapshotDelay' => 5,
                    'snapshotMaxRetries' => 3,
                ),
            ),
            'streaming' => array(
                // kucoin does not support built-in ws protocol-level ping-pong
                // instead it requires a custom json-based text ping-pong
                // https://docs.kucoin.com/#ping
                'ping' => array($this, 'ping'),
            ),
        ));
    }

    public function negotiate($privateChannel, $params = array ()) {
        $connectId = $privateChannel ? 'private' : 'public';
        $urls = $this->safe_value($this->options, 'urls', array());
        if (is_array($urls) && array_key_exists($connectId, $urls)) {
            return $urls[$connectId];
        }
        // we store an awaitable to the url
        // so that multiple calls don't asynchronously
        // fetch different $urls and overwrite each other
        $urls[$connectId] = $this->spawn(array($this, 'negotiate_helper'), $privateChannel, $params);
        $this->options['urls'] = $urls;
        return $urls[$connectId];
    }

    public function negotiate_helper($privateChannel, $params = array ()) {
        return Async\async(function () use ($privateChannel, $params) {
            $response = null;
            $connectId = $privateChannel ? 'private' : 'public';
            if ($privateChannel) {
                $response = Async\await($this->privatePostBulletPrivate ($params));
                //
                //     {
                //         code => "200000",
                //         $data => {
                //             $instanceServers => array(
                //                 {
                //                     $pingInterval =>  50000,
                //                     $endpoint => "wss://push-private.kucoin.com/endpoint",
                //                     protocol => "websocket",
                //                     encrypt => true,
                //                     pingTimeout => 10000
                //                 }
                //             ),
                //             $token => "2neAiuYvAU61ZDXANAGAsiL4-iAExhsBXZxftpOeh_55i3Ysy2q2LEsEWU64mdzUOPusi34M_wGoSf7iNyEWJ1UQy47YbpY4zVdzilNP-Bj3iXzrjjGlWtiYB9J6i9GjsxUuhPw3BlrzazF6ghq4Lzf7scStOz3KkxjwpsOBCH4=.WNQmhZQeUKIkh97KYgU0Lg=="
                //         }
                //     }
                //
            } else {
                $response = Async\await($this->publicPostBulletPublic ($params));
            }
            $data = $this->safe_value($response, 'data', array());
            $instanceServers = $this->safe_value($data, 'instanceServers', array());
            $firstInstanceServer = $this->safe_value($instanceServers, 0);
            $pingInterval = $this->safe_integer($firstInstanceServer, 'pingInterval');
            $endpoint = $this->safe_string($firstInstanceServer, 'endpoint');
            $token = $this->safe_string($data, 'token');
            $result = $endpoint . '?' . $this->urlencode(array(
                'token' => $token,
                'privateChannel' => $privateChannel,
                'connectId' => $connectId,
            ));
            $client = $this->client($result);
            $client->keepAlive = $pingInterval;
            return $result;
        }) ();
    }

    public function request_id() {
        $requestId = $this->sum($this->safe_integer($this->options, 'requestId', 0), 1);
        $this->options['requestId'] = $requestId;
        return $requestId;
    }

    public function subscribe($url, $messageHash, $subscriptionHash, $params = array (), $subscription = null) {
        return Async\async(function () use ($url, $messageHash, $subscriptionHash, $params, $subscription) {
            $requestId = (string) $this->request_id();
            $request = array(
                'id' => $requestId,
                'type' => 'subscribe',
                'topic' => $subscriptionHash,
                'response' => true,
            );
            $message = array_merge($request, $params);
            $client = $this->client($url);
            if (!(is_array($client->subscriptions) && array_key_exists($subscriptionHash, $client->subscriptions))) {
                $client->subscriptions[$requestId] = $subscriptionHash;
            }
            return Async\await($this->watch($url, $messageHash, $message, $subscriptionHash, $subscription));
        }) ();
    }

    public function watch_ticker(string $symbol, $params = array ()) {
        return Async\async(function () use ($symbol, $params) {
            /**
             * watches a price ticker, a statistical calculation with the information calculated over the past 24 hours for a specific $market
             * @param {string} $symbol unified $symbol of the $market to fetch the ticker for
             * @param {array} [$params] extra parameters specific to the kucoin api endpoint
             * @return {array} a ~@link https://docs.ccxt.com/#/?id=ticker-structure ticker structure~
             */
            Async\await($this->load_markets());
            $market = $this->market($symbol);
            $symbol = $market['symbol'];
            $url = Async\await($this->negotiate(false));
            list($method, $query) = $this->handle_option_and_params($params, 'watchTicker', 'method', '/market/snapshot');
            $topic = $method . ':' . $market['id'];
            $messageHash = 'ticker:' . $symbol;
            return Async\await($this->subscribe($url, $messageHash, $topic, $query));
        }) ();
    }

    public function handle_ticker(Client $client, $message) {
        //
        // market/snapshot
        //
        // updates come in every 2 sec unless there
        // were no changes since the previous update
        //
        //     {
        //         "data" => {
        //             "sequence" => "1545896669291",
        //             "data" => array(
        //                 "trading" => true,
        //                 "symbol" => "KCS-BTC",
        //                 "buy" => 0.00011,
        //                 "sell" => 0.00012,
        //                 "sort" => 100,
        //                 "volValue" => 3.13851792584, // total
        //                 "baseCurrency" => "KCS",
        //                 "market" => "BTC",
        //                 "quoteCurrency" => "BTC",
        //                 "symbolCode" => "KCS-BTC",
        //                 "datetime" => 1548388122031,
        //                 "high" => 0.00013,
        //                 "vol" => 27514.34842,
        //                 "low" => 0.0001,
        //                 "changePrice" => -1.0e-5,
        //                 "changeRate" => -0.0769,
        //                 "lastTradedPrice" => 0.00012,
        //                 "board" => 0,
        //                 "mark" => 0
        //             }
        //         ),
        //         "subject" => "trade.snapshot",
        //         "topic" => "/market/snapshot:KCS-BTC",
        //         "type" => "message"
        //     }
        //
        // market/ticker
        //
        //     {
        //         type => 'message',
        //         $topic => '/market/ticker:BTC-USDT',
        //         subject => 'trade.ticker',
        //         $data => {
        //             bestAsk => '62163',
        //             bestAskSize => '0.99011388',
        //             bestBid => '62162.9',
        //             bestBidSize => '0.04794181',
        //             price => '62162.9',
        //             sequence => '1621383371852',
        //             size => '0.00832274',
        //             time => 1634641987564
        //         }
        //     }
        //
        $topic = $this->safe_string($message, 'topic');
        $market = null;
        if ($topic !== null) {
            $parts = explode(':', $topic);
            $marketId = $this->safe_string($parts, 1);
            $market = $this->safe_market($marketId, $market, '-');
        }
        $data = $this->safe_value($message, 'data', array());
        $rawTicker = $this->safe_value($data, 'data', $data);
        $ticker = $this->parse_ticker($rawTicker, $market);
        $symbol = $ticker['symbol'];
        $this->tickers[$symbol] = $ticker;
        $messageHash = 'ticker:' . $symbol;
        $client->resolve ($ticker, $messageHash);
    }

    public function watch_ohlcv(string $symbol, $timeframe = '1m', ?int $since = null, ?int $limit = null, $params = array ()) {
        return Async\async(function () use ($symbol, $timeframe, $since, $limit, $params) {
            /**
             * watches historical candlestick data containing the open, high, low, and close price, and the volume of a $market
             * @param {string} $symbol unified $symbol of the $market to fetch OHLCV data for
             * @param {string} $timeframe the length of time each candle represents
             * @param {int} [$since] timestamp in ms of the earliest candle to fetch
             * @param {int} [$limit] the maximum amount of candles to fetch
             * @param {array} [$params] extra parameters specific to the kucoin api endpoint
             * @return {int[][]} A list of candles ordered, open, high, low, close, volume
             */
            Async\await($this->load_markets());
            $url = Async\await($this->negotiate(false));
            $market = $this->market($symbol);
            $symbol = $market['symbol'];
            $period = $this->safe_string($this->timeframes, $timeframe, $timeframe);
            $topic = '/market/candles:' . $market['id'] . '_' . $period;
            $messageHash = 'candles:' . $symbol . ':' . $timeframe;
            $ohlcv = Async\await($this->subscribe($url, $messageHash, $topic, $params));
            if ($this->newUpdates) {
                $limit = $ohlcv->getLimit ($symbol, $limit);
            }
            return $this->filter_by_since_limit($ohlcv, $since, $limit, 0, true);
        }) ();
    }

    public function handle_ohlcv(Client $client, $message) {
        //
        //     {
        //         $data => array(
        //             $symbol => 'BTC-USDT',
        //             $candles => array(
        //                 '1624881240',
        //                 '34138.8',
        //                 '34121.6',
        //                 '34138.8',
        //                 '34097.9',
        //                 '3.06097133',
        //                 '104430.955068564'
        //             ),
        //             time => 1624881284466023700
        //         ),
        //         subject => 'trade.candles.update',
        //         $topic => '/market/candles:BTC-USDT_1min',
        //         type => 'message'
        //     }
        //
        $data = $this->safe_value($message, 'data', array());
        $marketId = $this->safe_string($data, 'symbol');
        $candles = $this->safe_value($data, 'candles', array());
        $topic = $this->safe_string($message, 'topic');
        $parts = explode('_', $topic);
        $interval = $this->safe_string($parts, 1);
        // use a reverse lookup in a static map instead
        $timeframe = $this->find_timeframe($interval);
        $market = $this->safe_market($marketId);
        $symbol = $market['symbol'];
        $messageHash = 'candles:' . $symbol . ':' . $timeframe;
        $this->ohlcvs[$symbol] = $this->safe_value($this->ohlcvs, $symbol, array());
        $stored = $this->safe_value($this->ohlcvs[$symbol], $timeframe);
        if ($stored === null) {
            $limit = $this->safe_integer($this->options, 'OHLCVLimit', 1000);
            $stored = new ArrayCacheByTimestamp ($limit);
            $this->ohlcvs[$symbol][$timeframe] = $stored;
        }
        $ohlcv = $this->parse_ohlcv($candles, $market);
        $stored->append ($ohlcv);
        $client->resolve ($stored, $messageHash);
    }

    public function watch_trades(string $symbol, ?int $since = null, ?int $limit = null, $params = array ()) {
        return Async\async(function () use ($symbol, $since, $limit, $params) {
            /**
             * get the list of most recent $trades for a particular $symbol
             * @param {string} $symbol unified $symbol of the $market to fetch $trades for
             * @param {int} [$since] timestamp in ms of the earliest trade to fetch
             * @param {int} [$limit] the maximum amount of $trades to fetch
             * @param {array} [$params] extra parameters specific to the kucoin api endpoint
             * @return {array[]} a list of ~@link https://docs.ccxt.com/en/latest/manual.html?#public-$trades trade structures~
             */
            Async\await($this->load_markets());
            $url = Async\await($this->negotiate(false));
            $market = $this->market($symbol);
            $symbol = $market['symbol'];
            $topic = '/market/match:' . $market['id'];
            $messageHash = 'trades:' . $symbol;
            $trades = Async\await($this->subscribe($url, $messageHash, $topic, $params));
            if ($this->newUpdates) {
                $limit = $trades->getLimit ($symbol, $limit);
            }
            return $this->filter_by_since_limit($trades, $since, $limit, 'timestamp', true);
        }) ();
    }

    public function handle_trade(Client $client, $message) {
        //
        //     {
        //         $data => array(
        //             sequence => '1568787654360',
        //             $symbol => 'BTC-USDT',
        //             side => 'buy',
        //             size => '0.00536577',
        //             price => '9345',
        //             takerOrderId => '5e356c4a9f1a790008f8d921',
        //             time => '1580559434436443257',
        //             type => 'match',
        //             makerOrderId => '5e356bffedf0010008fa5d7f',
        //             tradeId => '5e356c4aeefabd62c62a1ece'
        //         ),
        //         subject => 'trade.l3match',
        //         topic => '/market/match:BTC-USDT',
        //         type => 'message'
        //     }
        //
        $data = $this->safe_value($message, 'data', array());
        $trade = $this->parse_trade($data);
        $symbol = $trade['symbol'];
        $messageHash = 'trades:' . $symbol;
        $trades = $this->safe_value($this->trades, $symbol);
        if ($trades === null) {
            $limit = $this->safe_integer($this->options, 'tradesLimit', 1000);
            $trades = new ArrayCache ($limit);
            $this->trades[$symbol] = $trades;
        }
        $trades->append ($trade);
        $client->resolve ($trades, $messageHash);
    }

    public function watch_order_book(string $symbol, ?int $limit = null, $params = array ()) {
        return Async\async(function () use ($symbol, $limit, $params) {
            /**
             * watches information on open orders with bid (buy) and ask (sell) prices, volumes and other data
             * @param {string} $symbol unified $symbol of the $market to fetch the order book for
             * @param {int} [$limit] the maximum amount of order book entries to return
             * @param {array} [$params] extra parameters specific to the kucoin api endpoint
             * @return {array} A dictionary of ~@link https://docs.ccxt.com/#/?id=order-book-structure order book structures~ indexed by $market symbols
             */
            //
            // https://docs.kucoin.com/#level-2-$market-data
            //
            // 1. After receiving the websocket Level 2 data flow, cache the data.
            // 2. Initiate a REST request to get the snapshot data of Level 2 order book.
            // 3. Playback the cached Level 2 data flow.
            // 4. Apply the new Level 2 data flow to the local snapshot to ensure that
            // the sequence of the new Level 2 update lines up with the sequence of
            // the previous Level 2 data. Discard all the message prior to that
            // sequence, and then playback the change to snapshot.
            // 5. Update the level2 full data based on sequence according to the
            // size. If the price is 0, ignore the messages and update the sequence.
            // If the size=0, update the sequence and remove the price of which the
            // size is 0 out of level 2. Fr other cases, please update the price.
            //
            if ($limit !== null) {
                if (($limit !== 20) && ($limit !== 100)) {
                    throw new ExchangeError($this->id . " watchOrderBook 'limit' argument must be null, 20 or 100");
                }
            }
            Async\await($this->load_markets());
            $url = Async\await($this->negotiate(false));
            $market = $this->market($symbol);
            $symbol = $market['symbol'];
            $topic = '/market/level2:' . $market['id'];
            $messageHash = 'orderbook:' . $symbol;
            $subscription = array(
                'method' => array($this, 'handle_order_book_subscription'),
                'symbol' => $symbol,
                'limit' => $limit,
            );
            $orderbook = Async\await($this->subscribe($url, $messageHash, $topic, $params, $subscription));
            return $orderbook->limit ();
        }) ();
    }

    public function handle_order_book(Client $client, $message) {
        //
        // initial snapshot is fetched with ccxt's fetchOrderBook
        // the feed does not include a snapshot, just the deltas
        //
        //     {
        //         "type":"message",
        //         "topic":"/market/level2:BTC-USDT",
        //         "subject":"trade.l2update",
        //         "data":{
        //             "sequenceStart":1545896669105,
        //             "sequenceEnd":1545896669106,
        //             "symbol":"BTC-USDT",
        //             "changes" => {
        //                 "asks" => [["6","1","1545896669105"]], // price, size, sequence
        //                 "bids" => [["4","1","1545896669106"]]
        //             }
        //         }
        //     }
        //
        $data = $this->safe_value($message, 'data');
        $marketId = $this->safe_string($data, 'symbol');
        $symbol = $this->safe_symbol($marketId, null, '-');
        $messageHash = 'orderbook:' . $symbol;
        $storedOrderBook = $this->orderbooks[$symbol];
        $nonce = $this->safe_integer($storedOrderBook, 'nonce');
        $deltaEnd = $this->safe_integer($data, 'sequenceEnd');
        if ($nonce === null) {
            $cacheLength = count($storedOrderBook->cache);
            $topic = $this->safe_string($message, 'topic');
            $subscription = $client->subscriptions[$topic];
            $limit = $this->safe_integer($subscription, 'limit');
            $snapshotDelay = $this->handle_option('watchOrderBook', 'snapshotDelay', 5);
            if ($cacheLength === $snapshotDelay) {
                $this->spawn(array($this, 'load_order_book'), $client, $messageHash, $symbol, $limit);
            }
            $storedOrderBook->cache[] = $data;
            return;
        } elseif ($nonce >= $deltaEnd) {
            return;
        }
        $this->handle_delta($storedOrderBook, $data);
        $client->resolve ($storedOrderBook, $messageHash);
    }

    public function get_cache_index($orderbook, $cache) {
        $firstDelta = $this->safe_value($cache, 0);
        $nonce = $this->safe_integer($orderbook, 'nonce');
        $firstDeltaStart = $this->safe_integer($firstDelta, 'sequenceStart');
        if ($nonce < $firstDeltaStart - 1) {
            return -1;
        }
        for ($i = 0; $i < count($cache); $i++) {
            $delta = $cache[$i];
            $deltaStart = $this->safe_integer($delta, 'sequenceStart');
            $deltaEnd = $this->safe_integer($delta, 'sequenceEnd');
            if (($nonce >= $deltaStart - 1) && ($nonce < $deltaEnd)) {
                return $i;
            }
        }
        return count($cache);
    }

    public function handle_delta($orderbook, $delta) {
        $orderbook['nonce'] = $this->safe_integer($delta, 'sequenceEnd');
        $timestamp = $this->safe_integer($delta, 'time');
        $orderbook['timestamp'] = $timestamp;
        $orderbook['datetime'] = $this->iso8601($timestamp);
        $changes = $this->safe_value($delta, 'changes');
        $bids = $this->safe_value($changes, 'bids', array());
        $asks = $this->safe_value($changes, 'asks', array());
        $storedBids = $orderbook['bids'];
        $storedAsks = $orderbook['asks'];
        $this->handle_bid_asks($storedBids, $bids);
        $this->handle_bid_asks($storedAsks, $asks);
    }

    public function handle_bid_asks($bookSide, $bidAsks) {
        for ($i = 0; $i < count($bidAsks); $i++) {
            $bidAsk = $this->parse_bid_ask($bidAsks[$i]);
            $bookSide->storeArray ($bidAsk);
        }
    }

    public function handle_order_book_subscription(Client $client, $message, $subscription) {
        $symbol = $this->safe_string($subscription, 'symbol');
        $limit = $this->safe_integer($subscription, 'limit');
        $this->orderbooks[$symbol] = $this->order_book(array(), $limit);
        // moved snapshot initialization to handleOrderBook to fix
        // https://github.com/ccxt/ccxt/issues/6820
        // the general idea is to fetch the snapshot after the first delta
        // but not before, because otherwise we cannot synchronize the feed
    }

    public function handle_subscription_status(Client $client, $message) {
        //
        //     {
        //         $id => '1578090438322',
        //         type => 'ack'
        //     }
        //
        $id = $this->safe_string($message, 'id');
        $subscriptionHash = $this->safe_string($client->subscriptions, $id);
        $subscription = $this->safe_value($client->subscriptions, $subscriptionHash);
        unset($client->subscriptions[$id]);
        $method = $this->safe_value($subscription, 'method');
        if ($method !== null) {
            $method($client, $message, $subscription);
        }
    }

    public function handle_system_status(Client $client, $message) {
        //
        // todo => answer the question whether handleSystemStatus should be renamed
        // and unified for any usage pattern that
        // involves system status and maintenance updates
        //
        //     {
        //         id => '1578090234088', // connectId
        //         type => 'welcome',
        //     }
        //
        return $message;
    }

    public function watch_orders(?string $symbol = null, ?int $since = null, ?int $limit = null, $params = array ()) {
        return Async\async(function () use ($symbol, $since, $limit, $params) {
            /**
             * watches information on multiple $orders made by the user
             * @param {string} $symbol unified $market $symbol of the $market $orders were made in
             * @param {int} [$since] the earliest time in ms to fetch $orders for
             * @param {int} [$limit] the maximum number of  orde structures to retrieve
             * @param {array} [$params] extra parameters specific to the kucoin api endpoint
             * @return {array[]} a list of ~@link https://docs.ccxt.com/#/?id=order-structure order structures~
             */
            Async\await($this->load_markets());
            $url = Async\await($this->negotiate(true));
            $topic = '/spotMarket/tradeOrders';
            $request = array(
                'privateChannel' => true,
            );
            $messageHash = 'orders';
            if ($symbol !== null) {
                $market = $this->market($symbol);
                $symbol = $market['symbol'];
                $messageHash = $messageHash . ':' . $symbol;
            }
            $orders = Async\await($this->subscribe($url, $messageHash, $topic, array_merge($request, $params)));
            if ($this->newUpdates) {
                $limit = $orders->getLimit ($symbol, $limit);
            }
            return $this->filter_by_symbol_since_limit($orders, $symbol, $since, $limit, true);
        }) ();
    }

    public function parse_ws_order_status($status) {
        $statuses = array(
            'open' => 'open',
            'filled' => 'closed',
            'match' => 'open',
            'update' => 'open',
            'canceled' => 'canceled',
        );
        return $this->safe_string($statuses, $status, $status);
    }

    public function parse_ws_order($order, $market = null) {
        //
        //     {
        //         'symbol' => 'XCAD-USDT',
        //         'orderType' => 'limit',
        //         'side' => 'buy',
        //         'orderId' => '6249167327218b000135e749',
        //         'type' => 'canceled',
        //         'orderTime' => 1648957043065280224,
        //         'size' => '100.452',
        //         'filledSize' => '0',
        //         'price' => '2.9635',
        //         'clientOid' => 'buy-XCAD-USDT-1648957043010159',
        //         'remainSize' => '0',
        //         'status' => 'done',
        //         'ts' => 1648957054031001037
        //     }
        //
        $id = $this->safe_string($order, 'orderId');
        $clientOrderId = $this->safe_string($order, 'clientOid');
        $orderType = $this->safe_string_lower($order, 'orderType');
        $price = $this->safe_string($order, 'price');
        $filled = $this->safe_string($order, 'filledSize');
        $amount = $this->safe_string($order, 'size');
        $rawType = $this->safe_string($order, 'type');
        $status = $this->parse_ws_order_status($rawType);
        $timestamp = $this->safe_integer($order, 'orderTime');
        $marketId = $this->safe_string($order, 'symbol');
        $market = $this->safe_market($marketId, $market);
        $symbol = $market['symbol'];
        $side = $this->safe_string_lower($order, 'side');
        return $this->safe_order(array(
            'info' => $order,
            'symbol' => $symbol,
            'id' => $id,
            'clientOrderId' => $clientOrderId,
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601($timestamp),
            'lastTradeTimestamp' => null,
            'type' => $orderType,
            'timeInForce' => null,
            'postOnly' => null,
            'side' => $side,
            'price' => $price,
            'stopPrice' => null,
            'triggerPrice' => null,
            'amount' => $amount,
            'cost' => null,
            'average' => null,
            'filled' => $filled,
            'remaining' => null,
            'status' => $status,
            'fee' => null,
            'trades' => null,
        ), $market);
    }

    public function handle_order(Client $client, $message) {
        $messageHash = 'orders';
        $data = $this->safe_value($message, 'data');
        $parsed = $this->parse_ws_order($data);
        $symbol = $this->safe_string($parsed, 'symbol');
        $orderId = $this->safe_string($parsed, 'id');
        if ($this->orders === null) {
            $limit = $this->safe_integer($this->options, 'ordersLimit', 1000);
            $this->orders = new ArrayCacheBySymbolById ($limit);
        }
        $cachedOrders = $this->orders;
        $orders = $this->safe_value($cachedOrders->hashmap, $symbol, array());
        $order = $this->safe_value($orders, $orderId);
        if ($order !== null) {
            // todo add others to calculate average etc
            $stopPrice = $this->safe_value($order, 'stopPrice');
            if ($stopPrice !== null) {
                $parsed['stopPrice'] = $stopPrice;
            }
            if ($order['status'] === 'closed') {
                $parsed['status'] = 'closed';
            }
        }
        $cachedOrders->append ($parsed);
        $client->resolve ($this->orders, $messageHash);
        $symbolSpecificMessageHash = $messageHash . ':' . $symbol;
        $client->resolve ($this->orders, $symbolSpecificMessageHash);
    }

    public function watch_my_trades(?string $symbol = null, ?int $since = null, ?int $limit = null, $params = array ()) {
        return Async\async(function () use ($symbol, $since, $limit, $params) {
            /**
             * watches information on multiple $trades made by the user
             * @param {string} $symbol unified $market $symbol of the $market $trades were made in
             * @param {int} [$since] the earliest time in ms to fetch $trades for
             * @param {int} [$limit] the maximum number of trade structures to retrieve
             * @param {array} [$params] extra parameters specific to the kucoin api endpoint
             * @return {array[]} a list of [trade structures]{@link https://docs.ccxt.com/#/?id=trade-structure
             */
            Async\await($this->load_markets());
            $url = Async\await($this->negotiate(true));
            $topic = '/spot/tradeFills';
            $request = array(
                'privateChannel' => true,
            );
            $messageHash = 'myTrades';
            if ($symbol !== null) {
                $market = $this->market($symbol);
                $symbol = $market['symbol'];
                $messageHash = $messageHash . ':' . $market['symbol'];
            }
            $trades = Async\await($this->subscribe($url, $messageHash, $topic, array_merge($request, $params)));
            if ($this->newUpdates) {
                $limit = $trades->getLimit ($symbol, $limit);
            }
            return $this->filter_by_symbol_since_limit($trades, $symbol, $since, $limit, true);
        }) ();
    }

    public function handle_my_trade(Client $client, $message) {
        $trades = $this->myTrades;
        if ($trades === null) {
            $limit = $this->safe_integer($this->options, 'tradesLimit', 1000);
            $trades = new ArrayCacheBySymbolById ($limit);
        }
        $data = $this->safe_value($message, 'data');
        $parsed = $this->parse_ws_trade($data);
        $trades->append ($parsed);
        $messageHash = 'myTrades';
        $client->resolve ($trades, $messageHash);
        $symbolSpecificMessageHash = $messageHash . ':' . $parsed['symbol'];
        $client->resolve ($trades, $symbolSpecificMessageHash);
    }

    public function parse_ws_trade($trade, $market = null) {
        //
        // {
        //     $fee => 0.00262148,
        //     $feeCurrency => 'USDT',
        //     $feeRate => 0.001,
        //     orderId => '62417436b29df8000183df2f',
        //     orderType => 'market',
        //     $price => 131.074,
        //     $side => 'sell',
        //     size => 0.02,
        //     $symbol => 'LTC-USDT',
        //     time => '1648456758734571745',
        //     $tradeId => '624174362e113d2f467b3043'
        //   }
        //
        $marketId = $this->safe_string($trade, 'symbol');
        $market = $this->safe_market($marketId, $market, '-');
        $symbol = $market['symbol'];
        $type = $this->safe_string($trade, 'orderType');
        $side = $this->safe_string($trade, 'side');
        $tradeId = $this->safe_string($trade, 'tradeId');
        $price = $this->safe_string($trade, 'price');
        $amount = $this->safe_string($trade, 'size');
        $order = $this->safe_string($trade, 'orderId');
        $timestamp = $this->safe_integer_product($trade, 'time', 0.000001);
        $feeCurrency = $market['quote'];
        $feeRate = $this->safe_string($trade, 'feeRate');
        $feeCost = $this->safe_string($trade, 'fee');
        $fee = array(
            'cost' => $feeCost,
            'rate' => $feeRate,
            'currency' => $feeCurrency,
        );
        return $this->safe_trade(array(
            'info' => $trade,
            'timestamp' => $timestamp,
            'datetime' => $this->iso8601($timestamp),
            'symbol' => $symbol,
            'id' => $tradeId,
            'order' => $order,
            'type' => $type,
            'takerOrMaker' => null,
            'side' => $side,
            'price' => $price,
            'amount' => $amount,
            'cost' => null,
            'fee' => $fee,
        ), $market);
    }

    public function watch_balance($params = array ()) {
        return Async\async(function () use ($params) {
            /**
             * watch balance and get the amount of funds available for trading or funds locked in orders
             * @param {array} [$params] extra parameters specific to the kucoin api endpoint
             * @return {array} a ~@link https://docs.ccxt.com/en/latest/manual.html?#balance-structure balance structure~
             */
            Async\await($this->load_markets());
            $url = Async\await($this->negotiate(true));
            $topic = '/account/balance';
            $request = array(
                'privateChannel' => true,
            );
            $messageHash = 'balance';
            return Async\await($this->subscribe($url, $messageHash, $topic, array_merge($request, $params)));
        }) ();
    }

    public function handle_balance(Client $client, $message) {
        //
        // {
        //     "id":"6217a451294b030001e3a26a",
        //     "type":"message",
        //     "topic":"/account/balance",
        //     "userId":"6217707c52f97f00012a67db",
        //     "channelType":"private",
        //     "subject":"account.balance",
        //     "data":{
        //        "accountId":"62177fe67810720001db2f18",
        //        "available":"89",
        //        "availableChange":"-30",
        //        "currency":"USDT",
        //        "hold":"0",
        //        "holdChange":"0",
        //        "relationContext":array(
        //        ),
        //        "relationEvent":"main.transfer",
        //        "relationEventId":"6217a451294b030001e3a26a",
        //        "time":"1645716561816",
        //        "total":"89"
        //     }
        //
        $data = $this->safe_value($message, 'data', array());
        $messageHash = 'balance';
        $currencyId = $this->safe_string($data, 'currency');
        $relationEvent = $this->safe_string($data, 'relationEvent');
        $requestAccountType = null;
        if ($relationEvent !== null) {
            $relationEventParts = explode('.', $relationEvent);
            $requestAccountType = $this->safe_string($relationEventParts, 0);
        }
        $selectedType = $this->safe_string_2($this->options, 'watchBalance', 'defaultType', 'trade'); // trade, main, margin or other
        $accountsByType = $this->safe_value($this->options, 'accountsByType');
        $uniformType = $this->safe_string($accountsByType, $requestAccountType, 'trade');
        if (!(is_array($this->balance) && array_key_exists($uniformType, $this->balance))) {
            $this->balance[$uniformType] = array();
        }
        $this->balance[$uniformType]['info'] = $data;
        $timestamp = $this->safe_integer($data, 'time');
        $this->balance[$uniformType]['timestamp'] = $timestamp;
        $this->balance[$uniformType]['datetime'] = $this->iso8601($timestamp);
        $code = $this->safe_currency_code($currencyId);
        $account = $this->account();
        $account['free'] = $this->safe_string($data, 'available');
        $account['used'] = $this->safe_string($data, 'hold');
        $account['total'] = $this->safe_string($data, 'total');
        $this->balance[$uniformType][$code] = $account;
        $this->balance[$uniformType] = $this->safe_balance($this->balance[$uniformType]);
        if ($uniformType === $selectedType) {
            $client->resolve ($this->balance[$uniformType], $messageHash);
        }
    }

    public function handle_subject(Client $client, $message) {
        //
        //     {
        //         "type":"message",
        //         "topic":"/market/level2:BTC-USDT",
        //         "subject":"trade.l2update",
        //         "data":{
        //             "sequenceStart":1545896669105,
        //             "sequenceEnd":1545896669106,
        //             "symbol":"BTC-USDT",
        //             "changes" => {
        //                 "asks" => [["6","1","1545896669105"]], // price, size, sequence
        //                 "bids" => [["4","1","1545896669106"]]
        //             }
        //         }
        //     }
        //
        $subject = $this->safe_string($message, 'subject');
        $methods = array(
            'trade.l2update' => array($this, 'handle_order_book'),
            'trade.ticker' => array($this, 'handle_ticker'),
            'trade.snapshot' => array($this, 'handle_ticker'),
            'trade.l3match' => array($this, 'handle_trade'),
            'trade.candles.update' => array($this, 'handle_ohlcv'),
            'account.balance' => array($this, 'handle_balance'),
            '/spot/tradeFills' => array($this, 'handle_my_trade'),
            'orderChange' => array($this, 'handle_order'),
        );
        $method = $this->safe_value($methods, $subject);
        if ($method === null) {
            return $message;
        } else {
            return $method($client, $message);
        }
    }

    public function ping($client) {
        // kucoin does not support built-in ws protocol-level ping-pong
        // instead it requires a custom json-based text ping-pong
        // https://docs.kucoin.com/#ping
        $id = (string) $this->request_id();
        return array(
            'id' => $id,
            'type' => 'ping',
        );
    }

    public function handle_pong(Client $client, $message) {
        $client->lastPong = $this->milliseconds();
        // https://docs.kucoin.com/#ping
    }

    public function handle_error_message(Client $client, $message) {
        //
        //    {
        //        id => '1',
        //        type => 'error',
        //        code => 415,
        //        $data => 'type is not supported'
        //    }
        //
        $data = $this->safe_string($message, 'data', '');
        $this->handle_errors(null, null, $client->url, null, null, $data, $message, null, null);
    }

    public function handle_message(Client $client, $message) {
        $type = $this->safe_string($message, 'type');
        $methods = array(
            // 'heartbeat' => $this->handleHeartbeat,
            'welcome' => array($this, 'handle_system_status'),
            'ack' => array($this, 'handle_subscription_status'),
            'message' => array($this, 'handle_subject'),
            'pong' => array($this, 'handle_pong'),
            'error' => array($this, 'handle_error_message'),
        );
        $method = $this->safe_value($methods, $type);
        if ($method !== null) {
            return $method($client, $message);
        }
    }
}