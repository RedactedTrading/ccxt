// -------------------------------------------------------------------------------

// PLEASE DO NOT EDIT THIS FILE, IT IS GENERATED AND WILL BE OVERWRITTEN:
// https://github.com/ccxt/ccxt/blob/master/CONTRIBUTING.md#how-to-contribute-code

// -------------------------------------------------------------------------------

namespace ccxt;

public partial class oceanex : Exchange
{
    public oceanex (object args = null): base(args) {}

    public async Task<object> publicGetMarkets (object parameters = null)
    {
        return await this.callAsync ("publicGetMarkets",parameters);
    }

    public async Task<object> publicGetTickersPair (object parameters = null)
    {
        return await this.callAsync ("publicGetTickersPair",parameters);
    }

    public async Task<object> publicGetTickersMulti (object parameters = null)
    {
        return await this.callAsync ("publicGetTickersMulti",parameters);
    }

    public async Task<object> publicGetOrderBook (object parameters = null)
    {
        return await this.callAsync ("publicGetOrderBook",parameters);
    }

    public async Task<object> publicGetOrderBookMulti (object parameters = null)
    {
        return await this.callAsync ("publicGetOrderBookMulti",parameters);
    }

    public async Task<object> publicGetFeesTrading (object parameters = null)
    {
        return await this.callAsync ("publicGetFeesTrading",parameters);
    }

    public async Task<object> publicGetTrades (object parameters = null)
    {
        return await this.callAsync ("publicGetTrades",parameters);
    }

    public async Task<object> publicGetTimestamp (object parameters = null)
    {
        return await this.callAsync ("publicGetTimestamp",parameters);
    }

    public async Task<object> publicPostK (object parameters = null)
    {
        return await this.callAsync ("publicPostK",parameters);
    }

    public async Task<object> privateGetKey (object parameters = null)
    {
        return await this.callAsync ("privateGetKey",parameters);
    }

    public async Task<object> privateGetMembersMe (object parameters = null)
    {
        return await this.callAsync ("privateGetMembersMe",parameters);
    }

    public async Task<object> privateGetOrders (object parameters = null)
    {
        return await this.callAsync ("privateGetOrders",parameters);
    }

    public async Task<object> privateGetOrdersFilter (object parameters = null)
    {
        return await this.callAsync ("privateGetOrdersFilter",parameters);
    }

    public async Task<object> privatePostOrders (object parameters = null)
    {
        return await this.callAsync ("privatePostOrders",parameters);
    }

    public async Task<object> privatePostOrdersMulti (object parameters = null)
    {
        return await this.callAsync ("privatePostOrdersMulti",parameters);
    }

    public async Task<object> privatePostOrderDelete (object parameters = null)
    {
        return await this.callAsync ("privatePostOrderDelete",parameters);
    }

    public async Task<object> privatePostOrderDeleteMulti (object parameters = null)
    {
        return await this.callAsync ("privatePostOrderDeleteMulti",parameters);
    }

    public async Task<object> privatePostOrdersClear (object parameters = null)
    {
        return await this.callAsync ("privatePostOrdersClear",parameters);
    }

}