import Exchange from './abstract/coinbase.js';
import { Int, OrderSide, OrderType } from './base/types.js';
/**
 * @class coinbase
 * @extends Exchange
 */
export default class coinbase extends Exchange {
    describe(): any;
    fetchTime(params?: {}): Promise<number>;
    fetchAccounts(params?: {}): Promise<any[]>;
    fetchAccountsV2(params?: {}): Promise<any[]>;
    fetchAccountsV3(params?: {}): Promise<any[]>;
    parseAccount(account: any): {
        id: string;
        type: string;
        code: any;
        info: any;
    };
    createDepositAddress(code: string, params?: {}): Promise<{
        currency: string;
        tag: string;
        address: string;
        info: any;
    }>;
    fetchMySells(symbol?: string, since?: Int, limit?: Int, params?: {}): Promise<import("./base/types.js").Trade[]>;
    fetchMyBuys(symbol?: string, since?: Int, limit?: Int, params?: {}): Promise<import("./base/types.js").Trade[]>;
    fetchTransactionsWithMethod(method: any, code?: string, since?: Int, limit?: Int, params?: {}): Promise<any>;
    fetchWithdrawals(code?: string, since?: Int, limit?: Int, params?: {}): Promise<any>;
    fetchDeposits(code?: string, since?: Int, limit?: Int, params?: {}): Promise<any>;
    parseTransactionStatus(status: any): string;
    parseTransaction(transaction: any, market?: any): {
        info: any;
        id: string;
        txid: string;
        timestamp: number;
        datetime: string;
        network: any;
        address: any;
        addressTo: any;
        addressFrom: any;
        tag: any;
        tagTo: any;
        tagFrom: any;
        type: string;
        amount: number;
        currency: any;
        status: string;
        updated: number;
        fee: {
            cost: number;
            currency: any;
        };
    };
    parseTrade(trade: any, market?: any): import("./base/types.js").Trade;
    fetchMarkets(params?: {}): Promise<any>;
    fetchMarketsV2(params?: {}): Promise<any[]>;
    fetchMarketsV3(params?: {}): Promise<any[]>;
    fetchCurrenciesFromCache(params?: {}): Promise<any>;
    fetchCurrencies(params?: {}): Promise<{}>;
    fetchTickers(symbols?: string[], params?: {}): Promise<any>;
    fetchTickersV2(symbols?: string[], params?: {}): Promise<any>;
    fetchTickersV3(symbols?: string[], params?: {}): Promise<any>;
    fetchTicker(symbol: string, params?: {}): Promise<any>;
    fetchTickerV2(symbol: string, params?: {}): Promise<import("./base/types.js").Ticker>;
    fetchTickerV3(symbol: string, params?: {}): Promise<any>;
    parseTicker(ticker: any, market?: any): import("./base/types.js").Ticker;
    parseBalance(response: any, params?: {}): import("./base/types.js").Balances;
    fetchBalance(params?: {}): Promise<import("./base/types.js").Balances>;
    fetchLedger(code?: string, since?: Int, limit?: Int, params?: {}): Promise<any>;
    parseLedgerEntryStatus(status: any): string;
    parseLedgerEntryType(type: any): string;
    parseLedgerEntry(item: any, currency?: any): {
        info: any;
        id: string;
        timestamp: number;
        datetime: string;
        direction: any;
        account: any;
        referenceId: any;
        referenceAccount: any;
        type: string;
        currency: any;
        amount: number;
        before: any;
        after: any;
        status: string;
        fee: any;
    };
    findAccountId(code: any): Promise<any>;
    prepareAccountRequest(limit?: Int, params?: {}): {
        account_id: string;
    };
    prepareAccountRequestWithCurrencyCode(code?: string, limit?: Int, params?: {}): Promise<{
        account_id: string;
    }>;
    createOrder(symbol: string, type: OrderType, side: OrderSide, amount: any, price?: any, params?: {}): Promise<import("./base/types.js").Order>;
    parseOrder(order: any, market?: any): import("./base/types.js").Order;
    parseOrderStatus(status: any): string;
    parseOrderType(type: any): string;
    parseTimeInForce(timeInForce: any): string;
    cancelOrder(id: string, symbol?: string, params?: {}): Promise<any>;
    cancelOrders(ids: any, symbol?: string, params?: {}): Promise<import("./base/types.js").Order[]>;
    fetchOrder(id: string, symbol?: string, params?: {}): Promise<import("./base/types.js").Order>;
    fetchOrders(symbol?: string, since?: Int, limit?: number, params?: {}): Promise<import("./base/types.js").Order[]>;
    fetchOrdersByStatus(status: any, symbol?: string, since?: Int, limit?: Int, params?: {}): Promise<import("./base/types.js").Order[]>;
    fetchOpenOrders(symbol?: string, since?: Int, limit?: Int, params?: {}): Promise<import("./base/types.js").Order[]>;
    fetchClosedOrders(symbol?: string, since?: Int, limit?: Int, params?: {}): Promise<import("./base/types.js").Order[]>;
    fetchCanceledOrders(symbol?: string, since?: Int, limit?: Int, params?: {}): Promise<import("./base/types.js").Order[]>;
    fetchOHLCV(symbol: string, timeframe?: string, since?: Int, limit?: Int, params?: {}): Promise<import("./base/types.js").OHLCV[]>;
    parseOHLCV(ohlcv: any, market?: any): number[];
    fetchTrades(symbol: string, since?: Int, limit?: Int, params?: {}): Promise<import("./base/types.js").Trade[]>;
    fetchMyTrades(symbol?: string, since?: Int, limit?: Int, params?: {}): Promise<import("./base/types.js").Trade[]>;
    fetchOrderBook(symbol: string, limit?: Int, params?: {}): Promise<import("./base/types.js").OrderBook>;
    fetchBidsAsks(symbols?: string[], params?: {}): Promise<import("./base/types.js").Dictionary<import("./base/types.js").Ticker>>;
    sign(path: any, api?: any[], method?: string, params?: {}, headers?: any, body?: any): {
        url: string;
        method: string;
        body: any;
        headers: any;
    };
    handleErrors(code: any, reason: any, url: any, method: any, headers: any, body: any, response: any, requestHeaders: any, requestBody: any): any;
}