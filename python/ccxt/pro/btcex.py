# -*- coding: utf-8 -*-

# PLEASE DO NOT EDIT THIS FILE, IT IS GENERATED AND WILL BE OVERWRITTEN:
# https://github.com/ccxt/ccxt/blob/master/CONTRIBUTING.md#how-to-contribute-code

import ccxt.async_support
from ccxt.async_support.base.ws.cache import ArrayCache, ArrayCacheBySymbolById, ArrayCacheByTimestamp
from ccxt.async_support.base.ws.client import Client
from typing import Optional
from ccxt.base.errors import ExchangeError
from ccxt.base.errors import ArgumentsRequired
from ccxt.base.errors import NotSupported


class btcex(ccxt.async_support.btcex):

    def describe(self):
        return self.deep_extend(super(btcex, self).describe(), {
            'has': {
                'ws': True,
                'watchBalance': True,
                'watchTicker': True,
                'watchTickers': False,
                'watchTrades': True,
                'watchMyTrades': True,
                'watchOrders': True,
                'watchOrderBook': True,
                'watchOHLCV': True,
            },
            'urls': {
                'api': {
                    'ws': 'wss://api.btcex.com/ws/api/v1',
                },
            },
            'options': {
                'watchOrderBook': {
                    'snapshotDelay': 0,
                    'maxRetries': 3,
                },
            },
            'streaming': {
                'ping': self.ping,
                'keepAlive': 5000,
            },
            'exceptions': {
            },
            'timeframes': {
                '1m': '1',
                '3m': '3',
                '5m': '4',
                '10m': '10',
                '15m': '15',
                '30m': '30',
                '1h': '60',
                '2h': '120',
                '3h': '180',
                '4h': '240',
                '6h': '360',
                '12h': '720',
                '1d': '1D',
            },
        })

    def request_id(self):
        requestId = self.sum(self.safe_integer(self.options, 'requestId', 0), 1)
        self.options['requestId'] = requestId
        return str(requestId)

    async def watch_balance(self, params={}):
        """
        query for balance and get the amount of funds available for trading or funds locked in orders
        see https://docs.btcex.com/#user-asset-asset_type
        :param dict params: extra parameters specific to the btcex api endpoint
        :param str params['type']: asset type WALLET, BTC,ETH,MARGIN,SPOT,PERPETUAL
        :returns dict: a `balance structure <https://docs.ccxt.com/en/latest/manual.html?#balance-structure>`
        """
        token = await self.authenticate(params)
        type = None
        type, params = self.handle_market_type_and_params('watchBalance', None, params)
        types = self.safe_value(self.options, 'accountsByType', {})
        assetType = self.safe_string(types, type, type)
        params = self.omit(params, 'type')
        messageHash = 'balancess'
        url = self.urls['api']['ws']
        subscribe = {
            'jsonrpc': '2.0',
            'id': self.request_id(),
            'method': '/private/subscribe',
            'params': {
                'access_token': token,
                'channels': [
                    'user.asset.' + assetType,
                ],
            },
        }
        request = self.deep_extend(subscribe, params)
        return await self.watch(url, messageHash, request, messageHash, request)

    def handle_balance(self, client: Client, message):
        #
        #     {
        #         "jsonrpc": "2.0",
        #         "method": "subscription",
        #         "params": {
        #             "channel": "user.asset.WALLET",
        #             "data": {
        #                 "WALLET": {
        #                     "total": "5578184962",
        #                     "coupon": "0",
        #                     "details": [
        #                         {
        #                             "available": "4999",
        #                             "freeze": "0",
        #                             "coin_type": "BTC",
        #                             "current_mark_price": "38000"
        #                         },
        #                         ...
        #                     ]
        #                 }
        #             }
        #         }
        #     }
        #
        params = self.safe_value(message, 'params', {})
        data = self.safe_value(params, 'data', {})
        messageHash = 'balancess'
        self.balance = self.parse_balance(data)
        client.resolve(self.balance, messageHash)

    async def watch_ohlcv(self, symbol: str, timeframe='1m', since: Optional[int] = None, limit: Optional[int] = None, params={}):
        """
        watches historical candlestick data containing the open, high, low, and close price, and the volume of a market.
        see https://docs.btcex.com/#chart-trades-instrument_name-resolution
        :param str symbol: unified symbol of the market to fetch OHLCV data for
        :param str timeframe: the length of time each candle represents.
        :param int|None since: timestamp in ms of the earliest candle to fetch
        :param int|None limit: the maximum amount of candles to fetch
        :param dict params: extra parameters specific to the bitfinex2 api endpoint
        :returns [[int]]: A list of candles ordered, open, high, low, close, volume
        """
        await self.load_markets()
        market = self.market(symbol)
        symbol = market['symbol']
        instrumentName = market['id']
        if market['spot']:
            instrumentName = market['baseId'] + '-' + market['quoteId']
        interval = self.safe_string(self.timeframes, timeframe, timeframe)
        messageHash = 'ohlcv:' + symbol + ':' + interval
        request = {
            'jsonrpc': '2.0',
            'id': self.request_id(),
            'method': '/public/subscribe',
            'params': {
                'channels': [
                    'chart.trades.' + instrumentName + '.' + interval,
                ],
            },
        }
        request = self.deep_extend(request, params)
        url = self.urls['api']['ws']
        ohlcv = await self.watch(url, messageHash, request, messageHash, request)
        if self.newUpdates:
            limit = ohlcv.getLimit(symbol, limit)
        return self.filter_by_since_limit(ohlcv, since, limit, 0, True)

    def handle_ohlcv(self, client: Client, message):
        #
        #     {
        #         "params": {
        #             "data": {
        #                 "tick": "1660095420",
        #                 "open": "22890.30000000",
        #                 "high": "22890.50000000",
        #                 "low": "22886.50000000",
        #                 "close": "22886.50000000",
        #                 "volume": "314.46800000",
        #                 "cost": "7197974.01690000"
        #             },
        #             "channel": "chart.trades.BTC-USDT-PERPETUAL.1"
        #         },
        #         "method": "subscription",
        #         "jsonrpc": "2.0"
        #     }
        #
        params = self.safe_value(message, 'params')
        channel = self.safe_string(params, 'channel')
        symbolInterval = channel[13:]
        dotIndex = symbolInterval.find('.')
        marketId = symbolInterval[0:dotIndex]
        timeframeId = symbolInterval[dotIndex + 1:]
        timeframe = self.find_timeframe(timeframeId)
        symbol = self.safe_symbol(marketId, None, '-')
        messageHash = 'ohlcv:' + symbol + ':' + timeframeId
        data = self.safe_value(params, 'data', {})
        ohlcv = self.parse_ohlcv(data)
        self.ohlcvs[symbol] = self.safe_value(self.ohlcvs, symbol, {})
        stored = self.safe_value(self.ohlcvs[symbol], timeframe)
        if stored is None:
            limit = self.safe_integer(self.options, 'OHLCVLimit', 1000)
            stored = ArrayCacheByTimestamp(limit)
            self.ohlcvs[symbol][timeframe] = stored
        stored.append(ohlcv)
        client.resolve(stored, messageHash)

    async def watch_ticker(self, symbol: str, params={}):
        """
        watches a price ticker, a statistical calculation with the information calculated over the past 24 hours for a specific market
        see https://docs.btcex.com/#ticker-instrument_name-interval
        :param str symbol: unified symbol of the market to fetch the ticker for
        :param dict params: extra parameters specific to the btcex api endpoint
        :returns dict: a `ticker structure <https://docs.ccxt.com/#/?id=ticker-structure>`
        """
        await self.load_markets()
        market = self.market(symbol)
        symbol = market['symbol']
        instrumentName = market['id']
        if market['spot']:
            instrumentName = market['baseId'] + '-' + market['quoteId']
        url = self.urls['api']['ws']
        messageHash = 'ticker:' + symbol
        request = {
            'jsonrpc': '2.0',
            'id': self.request_id(),
            'method': '/public/subscribe',
            'params': {
                'channels': [
                    'ticker.' + instrumentName + '.raw',
                ],
            },
        }
        request = self.deep_extend(request, params)
        return await self.watch(url, messageHash, request, messageHash)

    def handle_ticker(self, client: Client, message):
        #
        #     {
        #         "params": {
        #             "data": {
        #                 "timestamp": "1660094543813",
        #                 "stats": {
        #                     "volume": "630219.70300000000008822",
        #                     "price_change": "-0.0378",
        #                     "low": "22659.50000000",
        #                     "turnover": "14648416962.26930706016719341",
        #                     "high": "23919.00000000"
        #                 },
        #                 "state": "open",
        #                 "last_price": "22890.00000000",
        #                 "instrument_name": "BTC-USDT-PERPETUAL",
        #                 "best_bid_price": "22888.60000000",
        #                 "best_bid_amount": "33.38500000",
        #                 "best_ask_price": "22889.40000000",
        #                 "best_ask_amount": "5.45200000",
        #                 "mark_price": "22890.5",
        #                 "underlying_price": "22891",
        #                 "open_interest": "33886.083"
        #             },
        #             "channel": "ticker.BTC-USDT-PERPETUAL.raw"
        #         },
        #         "method": "subscription",
        #         "jsonrpc": "2.0"
        #     }
        #
        params = self.safe_value(message, 'params')
        data = self.safe_value(params, 'data')
        ticker = self.parse_ticker(data)
        symbol = self.safe_string(ticker, 'symbol')
        messageHash = 'ticker:' + symbol
        self.tickers[symbol] = ticker
        client.resolve(ticker, messageHash)

    async def watch_trades(self, symbol: str, since: Optional[int] = None, limit: Optional[int] = None, params={}):
        """
        get the list of most recent trades for a particular symbol
        see https://docs.btcex.com/#trades-instrument_name-interval
        :param str symbol: unified symbol of the market to fetch trades for
        :param int|None since: timestamp in ms of the earliest trade to fetch
        :param int|None limit: the maximum amount of    trades to fetch
        :param dict params: extra parameters specific to the btcex api endpoint
        :returns [dict]: a list of `trade structures <https://docs.ccxt.com/en/latest/manual.html?#public-trades>`
        """
        await self.load_markets()
        market = self.market(symbol)
        symbol = market['symbol']
        url = self.urls['api']['ws']
        messageHash = 'trades:' + symbol
        request = {
            'jsonrpc': '2.0',
            'id': self.request_id(),
            'method': '/public/subscribe',
            'params': {
                'channels': [
                    'trades.' + market['id'] + '.raw',
                ],
            },
        }
        request = self.deep_extend(request, params)
        trades = await self.watch(url, messageHash, request, messageHash, request)
        if self.newUpdates:
            limit = trades.getLimit(symbol, limit)
        return self.filter_by_since_limit(trades, since, limit, 'timestamp', True)

    def handle_trades(self, client: Client, message):
        #
        #     {
        #         "jsonrpc": "2.0",
        #         "method": "subscription",
        #         "params": {
        #             "channel": "trades.BTC-USDT-PERPETUAL.raw",
        #             "data": [{
        #                 "timestamp": "1660093462553",
        #                 "price": "22815.9",
        #                 "amount": "4.479",
        #                 "iv": "0",
        #                 "direction": "sell",
        #                 "instrument_name": "BTC-USDT-PERPETUAL",
        #                 "trade_id": "227976617",
        #                 "mark_price": "22812.7"
        #             }]
        #         }
        #     }
        #
        params = self.safe_value(message, 'params', {})
        fullChannel = self.safe_string(params, 'channel')
        parts = fullChannel.split('.')
        marketId = parts[1]
        symbol = self.safe_symbol(marketId)
        messageHash = 'trades:' + symbol
        stored = self.safe_value(self.trades, symbol)
        if stored is None:
            limit = self.safe_integer(self.options, 'tradesLimit', 1000)
            stored = ArrayCache(limit)
            self.trades[symbol] = stored
        rawTrades = self.safe_value(params, 'data', [])
        for i in range(0, len(rawTrades)):
            rawTrade = rawTrades[i]
            trade = self.parse_trade(rawTrade, None)
            stored.append(trade)
        self.trades[symbol] = stored
        client.resolve(stored, messageHash)

    async def watch_my_trades(self, symbol: Optional[str] = None, since: Optional[int] = None, limit: Optional[int] = None, params={}):
        """
        watch all trades made by the user
        see https://docs.btcex.com/#user-trades-instrument_name-interval
        :param str symbol: unified market symbol
        :param int|None since: the earliest time in ms to fetch trades for
        :param int|None limit: the maximum number of trades structures to retrieve
        :param dict params: extra parameters specific to the bibox api endpoint
        :returns [dict]: a list of `trade structures <https://docs.ccxt.com/#/?id=trade-structure>`
        """
        if symbol is None:
            raise ArgumentsRequired(self.id + ' watchMyTrades() requires a symbol argument')
        await self.load_markets()
        token = await self.authenticate()
        market = self.market(symbol)
        symbol = market['symbol']
        url = self.urls['api']['ws']
        messageHash = 'myTrades:' + symbol
        request = {
            'jsonrpc': '2.0',
            'id': self.request_id(),
            'method': '/private/subscribe',
            'params': {
                'access_token': token,
                'channels': [
                    'user.trades.' + market['id'] + '.raw',
                ],
            },
        }
        trades = await self.watch(url, messageHash, request, messageHash)
        if self.newUpdates:
            limit = trades.getLimit(symbol, limit)
        return self.filter_by_symbol_since_limit(trades, symbol, since, limit, True)

    def handle_my_trades(self, client: Client, message):
        #
        #     {
        #         "jsonrpc": "2.0",
        #         "method": "subscription",
        #         "params": {
        #             "channel": "user.trades.BTC-14AUG20.raw",
        #             "data": [{
        #                 "direction": "sell",
        #                 "amount": "1",
        #                 "price": "33000",
        #                 "iv": "0",
        #                 "fee": "0",
        #                 "timestamp": 1626148488157,
        #                 "trade_id": "1",
        #                 "order_id": "160717710099746816",
        #                 "instrument_name": "BTC-24SEP21",
        #                 "order_type": "limit",
        #                 "fee_coin_type": "USDT",
        #                 "index_price": "33157.63"
        #             }]
        #         }
        #     }
        #
        params = self.safe_value(message, 'params', {})
        channel = self.safe_string(params, 'channel', '')
        endIndex = channel.find('.raw')
        marketId = channel[12:endIndex]
        symbol = self.safe_symbol(marketId, None, '-')
        rawTrades = self.safe_value(params, 'data', [])
        stored = self.myTrades
        if stored is None:
            limit = self.safe_integer(self.options, 'tradesLimit', 1000)
            stored = ArrayCacheBySymbolById(limit)
        for i in range(0, len(rawTrades)):
            rawTrade = rawTrades[i]
            trade = self.parse_trade(rawTrade)
            stored.append(trade)
        self.myTrades = stored
        messageHash = 'myTrades:' + symbol
        client.resolve(stored, messageHash)

    async def watch_orders(self, symbol: Optional[str] = None, since: Optional[int] = None, limit: Optional[int] = None, params={}):
        """
        watches information on multiple orders made by the user
        see https://docs.btcex.com/#user-changes-kind-currency-interval
        :param str symbol: unified market symbol of the market orders were made in
        :param int|None since: the earliest time in ms to fetch orders for
        :param int|None limit: the maximum number of  orde structures to retrieve
        :param dict params: extra parameters specific to the btcex api endpoint
        :returns [dict]: a list of `order structures <https://docs.ccxt.com/#/?id=order-structure>`
        """
        if symbol is None:
            raise ArgumentsRequired(self.id + 'watchesOrders() requires a symbol')
        await self.load_markets()
        token = await self.authenticate()
        market = self.market(symbol)
        symbol = market['symbol']
        url = self.urls['api']['ws']
        message = {
            'jsonrpc': '2.0',
            'id': self.request_id(),
            'method': '/private/subscribe',
            'params': {
                'access_token': token,
                'channels': [
                    'user.orders.' + market['id'] + '.raw',
                ],
            },
        }
        messageHash = 'orders:' + symbol
        request = self.deep_extend(message, params)
        orders = await self.watch(url, messageHash, request, messageHash)
        if self.newUpdates:
            limit = orders.getLimit(symbol, limit)
        return self.filter_by_symbol_since_limit(orders, symbol, since, limit)

    def handle_order(self, client: Client, message):
        #
        #     {
        #         "jsonrpc": "2.0",
        #         "method": "subscription",
        #         "params": {
        #             "channel": "user.orders.BTC-14AUG20.raw",
        #             "data": {
        #                 "amount": "1",
        #                 "price": "11895.00",
        #                 "direction": "buy",
        #                 "version": 0,
        #                 "order_state": "filled",
        #                 "instrument_name": "BTC-14AUG20",
        #                 "time_in_force": "good_til_cancelled",
        #                 "last_update_timestamp": 1597130534567,
        #                 "filled_amount": "1",
        #                 "average_price": "11770.00",
        #                 "order_id": "39007591615041536",
        #                 "creation_timestamp": 1597130534567,
        #                 "order_type": "limit"
        #             }
        #     }
        #
        params = self.safe_value(message, 'params', {})
        rawOrder = self.safe_value(params, 'data', {})
        cachedOrders = self.orders
        if cachedOrders is None:
            limit = self.safe_integer(self.options, 'ordersLimit', 1000)
            cachedOrders = ArrayCacheBySymbolById(limit)
        order = self.parse_order(rawOrder)
        symbol = self.safe_string(order, 'symbol')
        messageHash = 'orders:' + symbol
        cachedOrders.append(order)
        self.orders = cachedOrders
        client.resolve(self.orders, messageHash)

    async def watch_order_book(self, symbol: str, limit: Optional[int] = None, params={}):
        """
        watches information on open orders with bid(buy) and ask(sell) prices, volumes and other data
        see https://docs.btcex.com/#book-instrument_name-interval
        :param str symbol: unified symbol of the market to fetch the order book for
        :param int|None limit: the maximum amount of order book entries to return
        :param dictConstructor params: extra parameters specific to the btcex api endpoint
        :param str|None params['type']: accepts l2 or l3 for level 2 or level 3 order book
        :returns dict: A dictionary of `order book structures <https://docs.ccxt.com/#/?id=order-book-structure>` indexed by market symbols
        """
        await self.load_markets()
        market = self.market(symbol)
        symbol = market['symbol']
        instrumentName = market['id']
        if market['spot']:
            instrumentName = market['baseId'] + '-' + market['quoteId']
        url = self.urls['api']['ws']
        params = self.omit(params, 'type')
        messageHash = 'orderbook:' + symbol
        subscribe = {
            'jsonrpc': '2.0',
            'id': self.request_id(),
            'method': '/public/subscribe',
            'params': {
                'channels': [
                    'book.' + instrumentName + '.raw',
                ],
            },
        }
        request = self.deep_extend(subscribe, params)
        orderbook = await self.watch(url, messageHash, request, messageHash)
        return orderbook.limit()

    def handle_order_book(self, client: Client, message):
        #
        #     {
        #         "params": {
        #             "data": {
        #                 "timestamp": 1626056933600,
        #                 "change_id": 1566764,
        #                 "asks": [
        #                     [
        #                         "new",
        #                         "34227.122",
        #                         "0.00554"
        #                     ],
        #                     ...
        #                 ],
        #                 "bids": [
        #                     [
        #                         "delete",
        #                         "34105.540",
        #                         "0"
        #                     ],
        #                     ...
        #                 ],
        #                 "instrument_name": "BTC-USDT"
        #             },
        #             "channel": "book.BTC-USDT.raw"
        #         },
        #         "method": "subscription",
        #         "jsonrpc": "2.0"
        #     }
        # `
        params = self.safe_value(message, 'params')
        data = self.safe_value(params, 'data')
        marketId = self.safe_string(data, 'instrument_name')
        symbol = self.safe_symbol(marketId, None, '-')
        storedOrderBook = self.safe_value(self.orderbooks, symbol)
        nonce = self.safe_integer(storedOrderBook, 'nonce')
        deltaNonce = self.safe_integer(data, 'change_id')
        messageHash = 'orderbook:' + symbol
        if nonce is None:
            cacheLength = len(storedOrderBook.cache)
            snapshotDelay = self.handle_option('watchOrderBook', 'snapshotDelay', 0)
            if cacheLength == snapshotDelay:
                limit = 0
                self.spawn(self.load_order_book, client, messageHash, symbol, limit)
            storedOrderBook.cache.append(data)
            return
        elif deltaNonce <= nonce:
            return
        self.handle_delta(storedOrderBook, data)
        client.resolve(storedOrderBook, messageHash)

    def get_cache_index(self, orderBook, cache):
        firstElement = cache[0]
        lastChangeId = self.safe_integer(firstElement, 'change_id')
        nonce = self.safe_integer(orderBook, 'nonce')
        if nonce < lastChangeId - 1:
            return -1
        for i in range(0, len(cache)):
            delta = cache[i]
            lastChangeId = self.safe_integer(delta, 'change_id')
            if nonce == lastChangeId - 1:
                # nonce is inside the cache
                # [d, d, n, d]
                return i
        return len(cache)

    def handle_delta(self, orderbook, delta):
        timestamp = self.safe_integer(delta, 'timestamp')
        orderbook['timestamp'] = timestamp
        orderbook['datetime'] = self.iso8601(timestamp)
        orderbook['nonce'] = self.safe_integer(delta, 'change_id')
        bids = self.safe_value(delta, 'bids', [])
        asks = self.safe_value(delta, 'asks', [])
        storedBids = orderbook['bids']
        storedAsks = orderbook['asks']
        self.handle_bid_asks(storedBids, bids)
        self.handle_bid_asks(storedAsks, asks)

    def handle_bid_asks(self, bookSide, bidAsks):
        for i in range(0, len(bidAsks)):
            bidAsk = self.parse_bid_ask(bidAsks[i], 1, 2)
            bookSide.storeArray(bidAsk)

    def handle_user(self, client: Client, message):
        params = self.safe_value(message, 'params')
        fullChannel = self.safe_string(params, 'channel')
        sliceUser = fullChannel[5:]
        endIndex = sliceUser.find('.')
        userChannel = sliceUser[0:endIndex]
        handlers = {
            'asset': self.handle_balance,
            'orders': self.handle_order,
            'trades': self.handle_my_trades,
        }
        handler = self.safe_value(handlers, userChannel)
        if handler is not None:
            return handler(client, message)
        raise NotSupported(self.id + ' received an unsupported message: ' + self.json(message))

    def handle_error_message(self, client: Client, message):
        #
        #     {
        #         id: '1',
        #         jsonrpc: '2.0',
        #         usIn: 1660140064049,
        #         usOut: 1660140064051,
        #         usDiff: 2,
        #         error: {code: 10000, message: 'Authentication Failure'}
        #     }
        #
        error = self.safe_value(message, 'error', {})
        raise ExchangeError(self.id + ' error: ' + self.json(error))

    def handle_authenticate(self, client: Client, message):
        #
        #     {
        #         id: '1',
        #         jsonrpc: '2.0',
        #         usIn: 1660140846671,
        #         usOut: 1660140846688,
        #         usDiff: 17,
        #         result: {
        #           access_token: 'xxxxxx43jIXYrF3VSm90ar+f5n447M3ll82AiFO58L85pxb/DbVf6Bn4ZyBX1i1tM/KYFBJ234ZkrUkwImUIEu8vY1PBh5JqaaaaaeGnao=',
        #           token_type: 'bearer',
        #           refresh_token: '/I56sUOB/zwpwo8X8Q0Z234bW8Lz1YNlXOXSP6C+ZJDWR+49CjVPr0Z3PVXoL3BOB234WxXtTid+YmNjQ8OqGn1MM9pQL5TKZ97s49SvaRc=',
        #           expires_in: 604014,
        #           scope: 'account:read_write block_trade:read_write trade:read_write wallet:read_write',
        #           m: '00000000006e446c6b44694759735570786e5668387335431274546e633867474d647772717a463924a6d3746756951334b637459653970576d63693143e6e335972584e48594c74674c4d416872564a4d56424c347438737938736f4645747263315374454e73324e546d346e5651792b69696279336647347737413d3d'
        #         }
        #     }
        #
        result = self.safe_value(message, 'result', {})
        expiresIn = self.safe_integer(result, 'expires_in', 0)
        self.options['expiresAt'] = self.sum(self.seconds(), expiresIn) * 1000
        accessToken = self.safe_string(result, 'access_token')
        client.resolve(accessToken, 'authenticated')

    def handle_subscription(self, client: Client, message):
        channels = self.safe_value(message, 'result', [])
        for i in range(0, len(channels)):
            fullChannel = channels[i]
            parts = fullChannel.split('.')
            channel = self.safe_string(parts, 0)
            marketId = self.safe_string(parts, 1)
            if channel == 'book':
                symbol = self.safe_symbol(marketId, None, '-')
                self.orderbooks[symbol] = self.order_book({})
                # get full depth book

    def handle_pong(self, client: Client, message):
        client.lastPong = self.milliseconds()

    def handle_message(self, client: Client, message):
        if message == 'PONG':
            self.handle_pong(client, message)
            return
        error = self.safe_value(message, 'error')
        if error is not None:
            return self.handle_error_message(client, message)
        result = self.safe_value(message, 'result', {})
        accessToken = self.safe_string(result, 'access_token')
        if accessToken is not None:
            return self.handle_authenticate(client, message)
        method = self.safe_string(message, 'method')
        if method == 'subscription':
            params = self.safe_value(message, 'params')
            fullChannel = self.safe_string(params, 'channel')
            parts = fullChannel.split('.')
            channel = self.safe_string(parts, 0)
            handlers = {
                'ticker': self.handle_ticker,
                'trades': self.handle_trades,
                'chart': self.handle_ohlcv,
                'balances': self.handle_balance,
                'trading': self.handle_order,
                'user': self.handle_user,
                'book': self.handle_order_book,
            }
            handler = self.safe_value(handlers, channel)
            if handler is not None:
                return handler(client, message)
        elif 'result' in message:
            self.handle_subscription(client, message)
        return message

    def authenticate(self, params={}):
        url = self.urls['api']['ws']
        client = self.client(url)
        messageHash = 'authenticated'
        expiresAt = self.safe_number(self.options, 'expiresAt')
        time = self.milliseconds()
        future = self.safe_value(client.subscriptions, messageHash)
        if (future is None) or (expiresAt <= time):
            request = {
                'jsonrpc': '2.0',
                'id': self.request_id(),
                'method': '/public/auth',
                'params': {
                    'grant_type': 'client_credentials',
                    'client_id': self.apiKey,
                    'client_secret': self.secret,
                },
            }
            message = self.extend(request, params)
            future = self.watch(url, messageHash, message)
            client.subscriptions[messageHash] = future
        return future

    def ping(self, client):
        return 'PING'