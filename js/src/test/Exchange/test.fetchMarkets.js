// ----------------------------------------------------------------------------

// PLEASE DO NOT EDIT THIS FILE, IT IS GENERATED AND WILL BE OVERWRITTEN:
// https://github.com/ccxt/ccxt/blob/master/CONTRIBUTING.md#how-to-contribute-code
// EDIT THE CORRESPONDENT .ts FILE INSTEAD

import assert from 'assert';
import testMarket from './base/test.market.js';
async function testFetchMarkets(exchange, skippedProperties) {
    const method = 'fetchMarkets';
    const markets = await exchange.fetchMarkets();
    assert(typeof markets === 'object', exchange.id + ' ' + method + ' must return an object. ' + exchange.json(markets));
    const marketValues = Object.values(markets);
    for (let i = 0; i < marketValues.length; i++) {
        testMarket(exchange, skippedProperties, method, marketValues[i]);
    }
}
export default testFetchMarkets;