import krakenRest from '../kraken.js';
import { Int, OrderSide, OrderType } from '../base/types.js';
import Client from '../base/ws/Client.js';
export default class kraken extends krakenRest {
    describe(): any;
    createOrderWs(symbol: string, type: OrderType, side: OrderSide, amount: number, price?: number, params?: {}): Promise<any>;
    handleCreateEditOrder(client: any, message: any): void;
    editOrderWs(id: string, symbol: string, type: OrderType, side: OrderSide, amount: number, price?: number, params?: {}): Promise<any>;
    cancelOrdersWs(ids: string[], symbol?: string, params?: {}): Promise<any>;
    cancelOrderWs(id: string, symbol?: string, params?: {}): Promise<any>;
    handleCancelOrder(client: any, message: any): void;
    cancelAllOrdersWs(symbol?: string, params?: {}): Promise<any>;
    handleCancelAllOrders(client: any, message: any): void;
    handleTicker(client: any, message: any, subscription: any): void;
    handleTrades(client: Client, message: any, subscription: any): void;
    handleOHLCV(client: Client, message: any, subscription: any): void;
    requestId(): any;
    watchPublic(name: any, symbol: any, params?: {}): Promise<any>;
    watchTicker(symbol: string, params?: {}): Promise<any>;
    watchTrades(symbol: string, since?: Int, limit?: Int, params?: {}): Promise<any>;
    watchOrderBook(symbol: string, limit?: Int, params?: {}): Promise<any>;
    watchOHLCV(symbol: string, timeframe?: string, since?: Int, limit?: Int, params?: {}): Promise<any>;
    loadMarkets(reload?: boolean, params?: {}): Promise<import("../base/types.js").Dictionary<import("../base/types.js").Market>>;
    watchHeartbeat(params?: {}): Promise<any>;
    handleHeartbeat(client: Client, message: any): void;
    handleOrderBook(client: Client, message: any, subscription: any): void;
    formatNumber(n: any, length: any): string;
    handleDeltas(bookside: any, deltas: any, timestamp?: any): any;
    handleSystemStatus(client: Client, message: any): any;
    authenticate(params?: {}): Promise<string>;
    watchPrivate(name: any, symbol?: string, since?: Int, limit?: Int, params?: {}): Promise<any>;
    watchMyTrades(symbol?: string, since?: Int, limit?: Int, params?: {}): Promise<any>;
    handleMyTrades(client: Client, message: any, subscription?: any): void;
    parseWsTrade(trade: any, market?: any): {
        id: string;
        order: string;
        info: any;
        timestamp: number;
        datetime: string;
        symbol: any;
        type: string;
        side: string;
        takerOrMaker: any;
        price: number;
        amount: number;
        cost: any;
        fee: any;
    };
    watchOrders(symbol?: string, since?: Int, limit?: Int, params?: {}): Promise<any>;
    handleOrders(client: Client, message: any, subscription?: any): void;
    parseWsOrder(order: any, market?: any): import("../base/types.js").Order;
    handleSubscriptionStatus(client: Client, message: any): void;
    handleErrorMessage(client: Client, message: any): boolean;
    handleMessage(client: Client, message: any): any;
}