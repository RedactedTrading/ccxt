using ccxt;
namespace Tests;

// PLEASE DO NOT EDIT THIS FILE, IT IS GENERATED AND WILL BE OVERWRITTEN:
// https://github.com/ccxt/ccxt/blob/master/CONTRIBUTING.md#how-to-contribute-code


public partial class testMainClass : BaseTest
{
    async static public Task testFetchDeposits(Exchange exchange, object skippedProperties, object code)
    {
        object method = "fetchDeposits";
        object transactions = await exchange.fetchDeposits(code);
        assert(((transactions is IList<object>) || (transactions.GetType().IsGenericType && transactions.GetType().GetGenericTypeDefinition().IsAssignableFrom(typeof(List<>)))), add(add(add(add(add(add(exchange.id, " "), method), " "), code), " must return an array. "), exchange.json(transactions)));
        object now = exchange.milliseconds();
        for (object i = 0; isLessThan(i, getArrayLength(transactions)); postFixIncrement(ref i))
        {
            testDepositWithdrawal(exchange, skippedProperties, method, getValue(transactions, i), code, now);
        }
        testSharedMethods.assertTimestampOrder(exchange, method, code, transactions);
    }

}