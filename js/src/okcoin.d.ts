import Exchange from './abstract/okcoin.js';
import { Int, OrderSide, OrderType } from './base/types.js';
/**
 * @class okcoin
 * @extends Exchange
 */
export default class okcoin extends Exchange {
    describe(): any;
    fetchTime(params?: {}): Promise<number>;
    fetchMarkets(params?: {}): Promise<any[]>;
    parseMarkets(markets: any): any[];
    parseMarket(market: any): any;
    fetchMarketsByType(type: any, params?: {}): Promise<any[]>;
    fetchCurrencies(params?: {}): Promise<{}>;
    fetchOrderBook(symbol: string, limit?: Int, params?: {}): Promise<import("./base/types.js").OrderBook>;
    parseTicker(ticker: any, market?: any): import("./base/types.js").Ticker;
    fetchTicker(symbol: string, params?: {}): Promise<import("./base/types.js").Ticker>;
    fetchTickersByType(type: any, symbols?: string[], params?: {}): Promise<any>;
    fetchTickers(symbols?: string[], params?: {}): Promise<any>;
    parseTrade(trade: any, market?: any): import("./base/types.js").Trade;
    fetchTrades(symbol: string, since?: Int, limit?: Int, params?: {}): Promise<import("./base/types.js").Trade[]>;
    parseOHLCV(ohlcv: any, market?: any): any[];
    fetchOHLCV(symbol: string, timeframe?: string, since?: Int, limit?: Int, params?: {}): Promise<import("./base/types.js").OHLCV[]>;
    parseAccountBalance(response: any): import("./base/types.js").Balances;
    parseFuturesBalance(response: any): import("./base/types.js").Balances;
    parseSwapBalance(response: any): import("./base/types.js").Balances;
    fetchBalance(params?: {}): Promise<import("./base/types.js").Balances>;
    parseBalanceByType(type: any, response: any): import("./base/types.js").Balances;
    createOrder(symbol: string, type: OrderType, side: OrderSide, amount: any, price?: any, params?: {}): Promise<any>;
    cancelOrder(id: string, symbol?: string, params?: {}): Promise<import("./base/types.js").Order>;
    parseOrderStatus(status: any): string;
    parseOrderSide(side: any): string;
    parseOrder(order: any, market?: any): import("./base/types.js").Order;
    fetchOrder(id: string, symbol?: string, params?: {}): Promise<import("./base/types.js").Order>;
    fetchOrdersByState(state: any, symbol?: string, since?: Int, limit?: Int, params?: {}): Promise<import("./base/types.js").Order[]>;
    fetchOpenOrders(symbol?: string, since?: Int, limit?: Int, params?: {}): Promise<import("./base/types.js").Order[]>;
    fetchClosedOrders(symbol?: string, since?: Int, limit?: Int, params?: {}): Promise<import("./base/types.js").Order[]>;
    parseDepositAddress(depositAddress: any, currency?: any): {
        currency: any;
        address: string;
        tag: string;
        info: any;
    };
    fetchDepositAddress(code: string, params?: {}): Promise<any>;
    transfer(code: string, amount: any, fromAccount: any, toAccount: any, params?: {}): Promise<{
        info: any;
        id: string;
        timestamp: any;
        datetime: any;
        currency: any;
        amount: number;
        fromAccount: string;
        toAccount: string;
        status: string;
    }>;
    parseTransfer(transfer: any, currency?: any): {
        info: any;
        id: string;
        timestamp: any;
        datetime: any;
        currency: any;
        amount: number;
        fromAccount: string;
        toAccount: string;
        status: string;
    };
    parseTransferStatus(status: any): string;
    withdraw(code: string, amount: any, address: any, tag?: any, params?: {}): Promise<{
        info: any;
        id: any;
        currency: any;
        amount: number;
        network: any;
        addressFrom: string;
        addressTo: string;
        address: any;
        tagFrom: any;
        tagTo: string;
        tag: string;
        status: string;
        type: any;
        updated: any;
        txid: string;
        timestamp: number;
        datetime: string;
        fee: {
            currency: any;
            cost: any;
        };
    }>;
    fetchDeposits(code?: string, since?: Int, limit?: Int, params?: {}): Promise<any>;
    fetchWithdrawals(code?: string, since?: Int, limit?: Int, params?: {}): Promise<any>;
    parseTransactionStatus(status: any): string;
    parseTransaction(transaction: any, currency?: any): {
        info: any;
        id: any;
        currency: any;
        amount: number;
        network: any;
        addressFrom: string;
        addressTo: string;
        address: any;
        tagFrom: any;
        tagTo: string;
        tag: string;
        status: string;
        type: any;
        updated: any;
        txid: string;
        timestamp: number;
        datetime: string;
        fee: {
            currency: any;
            cost: any;
        };
    };
    parseMyTrade(pair: any, market?: any): import("./base/types.js").Trade;
    parseMyTrades(trades: any, market?: any, since?: Int, limit?: Int, params?: {}): any;
    fetchMyTrades(symbol?: string, since?: Int, limit?: Int, params?: {}): Promise<any>;
    fetchOrderTrades(id: string, symbol?: string, since?: Int, limit?: Int, params?: {}): Promise<any>;
    fetchPosition(symbol: string, params?: {}): Promise<any>;
    fetchPositions(symbols?: string[], params?: {}): Promise<any>;
    fetchLedger(code?: string, since?: Int, limit?: Int, params?: {}): Promise<any>;
    parseLedgerEntryType(type: any): string;
    parseLedgerEntry(item: any, currency?: any): {
        info: any;
        id: string;
        account: any;
        referenceId: string;
        referenceAccount: any;
        type: string;
        currency: any;
        symbol: any;
        amount: number;
        before: any;
        after: number;
        status: string;
        timestamp: number;
        datetime: string;
        fee: {
            cost: number;
            currency: any;
        };
    };
    sign(path: any, api?: string, method?: string, params?: {}, headers?: any, body?: any): {
        url: string;
        method: string;
        body: any;
        headers: any;
    };
    getPathAuthenticationType(path: any): string;
    handleErrors(code: any, reason: any, url: any, method: any, headers: any, body: any, response: any, requestHeaders: any, requestBody: any): any;
}