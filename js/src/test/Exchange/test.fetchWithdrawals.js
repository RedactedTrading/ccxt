// ----------------------------------------------------------------------------

// PLEASE DO NOT EDIT THIS FILE, IT IS GENERATED AND WILL BE OVERWRITTEN:
// https://github.com/ccxt/ccxt/blob/master/CONTRIBUTING.md#how-to-contribute-code
// EDIT THE CORRESPONDENT .ts FILE INSTEAD

import assert from 'assert';
import testSharedMethods from './base/test.sharedMethods.js';
import testTransaction from './base/test.depositWithdrawal.js';
async function testFetchWithdrawals(exchange, skippedProperties, code) {
    const method = 'fetchWithdrawals';
    const transactions = await exchange.fetchWithdrawals(code);
    assert(Array.isArray(transactions), exchange.id + ' ' + method + ' ' + code + ' must return an array. ' + exchange.json(transactions));
    const now = exchange.milliseconds();
    for (let i = 0; i < transactions.length; i++) {
        testTransaction(exchange, skippedProperties, method, transactions[i], code, now);
    }
    testSharedMethods.assertTimestampOrder(exchange, method, code, transactions);
}
export default testFetchWithdrawals;