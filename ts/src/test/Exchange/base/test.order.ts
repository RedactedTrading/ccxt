
import testSharedMethods from './test.sharedMethods.js';
import testTrade from './test.trade.js';

function testOrder (exchange, skippedProperties, method, entry, symbol, now) {
    const format = {
        'info': {},
        'id': '123',
        'clientOrderId': '1234',
        'timestamp': 1649373600000,
        'datetime': '2022-04-07T23:20:00.000Z',
        'lastTradeTimestamp': 1649373610000,
        'symbol': 'XYZ/USDT',
        'type': 'limit',
        'timeInForce': 'GTC',
        'postOnly': true,
        'side': 'sell',
        'price': exchange.parseNumber ('1.23456'),
        'stopPrice': exchange.parseNumber ('1.1111'),
        'amount': exchange.parseNumber ('1.23'),
        'cost': exchange.parseNumber ('2.34'),
        'average': exchange.parseNumber ('1.234'),
        'filled': exchange.parseNumber ('1.23'),
        'remaining': exchange.parseNumber ('0.123'),
        'status': 'ok',
        'fee': {},
        'trades': [],
    };
    const emptyAllowedFor = [ 'clientOrderId', 'stopPrice', 'trades' ]; // todo: we need more detailed property to skip the exchanges, that return only order id when executing order (in createOrder)
    testSharedMethods.assertStructure (exchange, skippedProperties, method, entry, format, emptyAllowedFor);
    testSharedMethods.assertTimestamp (exchange, skippedProperties, method, entry, now);
    //
    testSharedMethods.assertInArray (exchange, skippedProperties, method, entry, 'timeInForce', [ 'GTC', 'GTK', 'IOC', 'FOK' ]);
    testSharedMethods.assertInArray (exchange, skippedProperties, method, entry, 'status', [ 'open', 'closed', 'canceled' ]);
    testSharedMethods.assertInArray (exchange, skippedProperties, method, entry, 'side', [ 'buy', 'sell' ]);
    testSharedMethods.assertInArray (exchange, skippedProperties, method, entry, 'postOnly', [ true, false ]);
    testSharedMethods.assertSymbol (exchange, skippedProperties, method, entry, 'symbol', symbol);
    testSharedMethods.assertGreater (exchange, skippedProperties, method, entry, 'price', '0');
    testSharedMethods.assertGreater (exchange, skippedProperties, method, entry, 'stopPrice', '0');
    testSharedMethods.assertGreater (exchange, skippedProperties, method, entry, 'cost', '0');
    testSharedMethods.assertGreater (exchange, skippedProperties, method, entry, 'average', '0');
    testSharedMethods.assertGreater (exchange, skippedProperties, method, entry, 'average', '0');
    testSharedMethods.assertGreaterOrEqual (exchange, skippedProperties, method, entry, 'filled', '0');
    testSharedMethods.assertGreaterOrEqual (exchange, skippedProperties, method, entry, 'remaining', '0');
    testSharedMethods.assertGreaterOrEqual (exchange, skippedProperties, method, entry, 'amount', '0');
    testSharedMethods.assertGreaterOrEqual (exchange, skippedProperties, method, entry, 'amount', exchange.safeString (entry, 'remaining'));
    testSharedMethods.assertGreaterOrEqual (exchange, skippedProperties, method, entry, 'amount', exchange.safeString (entry, 'filled'));
    if (!('trades' in skippedProperties)) {
        if (entry['trades'] !== undefined) {
            for (let i = 0; i < entry['trades'].length; i++) {
                testTrade (exchange, skippedProperties, method, entry['trades'][i], symbol, now);
            }
        }
    }
    testSharedMethods.assertFeeStructure (exchange, skippedProperties, method, entry, 'fee');
}

export default testOrder;