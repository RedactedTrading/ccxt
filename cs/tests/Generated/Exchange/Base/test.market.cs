using ccxt;
namespace Tests;

// PLEASE DO NOT EDIT THIS FILE, IT IS GENERATED AND WILL BE OVERWRITTEN:
// https://github.com/ccxt/ccxt/blob/master/CONTRIBUTING.md#how-to-contribute-code


public partial class testMainClass : BaseTest
{
    public static void testMarket(Exchange exchange, object skippedProperties, object method, object market)
    {
        object format = new Dictionary<string, object>() {
            { "id", "btcusd" },
            { "symbol", "BTC/USD" },
            { "base", "BTC" },
            { "quote", "USD" },
            { "taker", exchange.parseNumber("0.0011") },
            { "maker", exchange.parseNumber("0.0009") },
            { "baseId", "btc" },
            { "quoteId", "usd" },
            { "active", false },
            { "type", "spot" },
            { "linear", false },
            { "inverse", false },
            { "spot", false },
            { "swap", false },
            { "future", false },
            { "option", false },
            { "margin", false },
            { "contract", false },
            { "contractSize", exchange.parseNumber("0.001") },
            { "expiry", 1656057600000 },
            { "expiryDatetime", "2022-06-24T08:00:00.000Z" },
            { "optionType", "put" },
            { "strike", exchange.parseNumber("56000") },
            { "settle", "XYZ" },
            { "settleId", "Xyz" },
            { "precision", new Dictionary<string, object>() {
                { "price", exchange.parseNumber("0.001") },
                { "amount", exchange.parseNumber("0.001") },
                { "cost", exchange.parseNumber("0.001") },
            } },
            { "limits", new Dictionary<string, object>() {
                { "amount", new Dictionary<string, object>() {
                    { "min", exchange.parseNumber("0.01") },
                    { "max", exchange.parseNumber("1000") },
                } },
                { "price", new Dictionary<string, object>() {
                    { "min", exchange.parseNumber("0.01") },
                    { "max", exchange.parseNumber("1000") },
                } },
                { "cost", new Dictionary<string, object>() {
                    { "min", exchange.parseNumber("0.01") },
                    { "max", exchange.parseNumber("1000") },
                } },
            } },
            { "info", new Dictionary<string, object>() {} },
        };
        object emptyAllowedFor = new List<object>() {"linear", "inverse", "settle", "settleId", "expiry", "expiryDatetime", "optionType", "strike", "margin", "contractSize"};
        testSharedMethods.assertStructure(exchange, skippedProperties, method, market, format, emptyAllowedFor);
        testSharedMethods.assertSymbol(exchange, skippedProperties, method, market, "symbol");
        object logText = testSharedMethods.logTemplate(exchange, method, market);
        //
        object validTypes = new List<object>() {"spot", "margin", "swap", "future", "option"};
        testSharedMethods.assertInArray(exchange, skippedProperties, method, market, "type", validTypes);
        object hasIndex = (inOp(market, "index")); // todo: add in all
        // check if string is consistent with 'type'
        if (isTrue(getValue(market, "spot")))
        {
            assert(isEqual(getValue(market, "type"), "spot"), add("\"type\" string should be \"spot\" when spot is true", logText));
        } else if (isTrue(getValue(market, "swap")))
        {
            assert(isEqual(getValue(market, "type"), "swap"), add("\"type\" string should be \"swap\" when swap is true", logText));
        } else if (isTrue(getValue(market, "future")))
        {
            assert(isEqual(getValue(market, "type"), "future"), add("\"type\" string should be \"future\" when future is true", logText));
        } else if (isTrue(getValue(market, "option")))
        {
            assert(isEqual(getValue(market, "type"), "option"), add("\"type\" string should be \"option\" when option is true", logText));
        } else if (isTrue(isTrue(hasIndex) && isTrue(getValue(market, "index"))))
        {
            // todo: add index in all implementations
            assert(isEqual(getValue(market, "type"), "index"), add("\"type\" string should be \"index\" when index is true", logText));
        }
        // margin check (todo: add margin as mandatory, instead of undefined)
        if (isTrue(getValue(market, "spot")))
        {
            // for spot market, 'margin' can be either true/false or undefined
            testSharedMethods.assertInArray(exchange, skippedProperties, method, market, "margin", new List<object>() {true, false, null});
        } else
        {
            // otherwise, it must be false or undefined
            testSharedMethods.assertInArray(exchange, skippedProperties, method, market, "margin", new List<object>() {false, null});
        }
        if (!isTrue((inOp(skippedProperties, "contractSize"))))
        {
            testSharedMethods.assertGreater(exchange, skippedProperties, method, market, "contractSize", "0");
        }
        // typical values
        testSharedMethods.assertGreater(exchange, skippedProperties, method, market, "expiry", "0");
        testSharedMethods.assertGreater(exchange, skippedProperties, method, market, "strike", "0");
        testSharedMethods.assertInArray(exchange, skippedProperties, method, market, "optionType", new List<object>() {"put", "call"});
        testSharedMethods.assertGreater(exchange, skippedProperties, method, market, "taker", "-100");
        testSharedMethods.assertGreater(exchange, skippedProperties, method, market, "maker", "-100");
        // 'contract' boolean check
        if (isTrue(isTrue(isTrue(isTrue(getValue(market, "future")) || isTrue(getValue(market, "swap"))) || isTrue(getValue(market, "option"))) || isTrue((isTrue(hasIndex) && isTrue(getValue(market, "index"))))))
        {
            // if it's some kind of contract market, then `conctract` should be true
            assert(getValue(market, "contract"), add("\"contract\" must be true when \"future\", \"swap\", \"option\" or \"index\" is true", logText));
        } else
        {
            assert(!isTrue(getValue(market, "contract")), add("\"contract\" must be false when neither \"future\", \"swap\",\"option\" or \"index\" is true", logText));
        }
        object isSwapOrFuture = isTrue(getValue(market, "swap")) || isTrue(getValue(market, "future"));
        object contractSize = exchange.safeString(market, "contractSize");
        // contract fields
        if (isTrue(getValue(market, "contract")))
        {
            // linear & inverse should have different values (true/false)
            // todo: expand logic on other market types
            if (isTrue(isSwapOrFuture))
            {
                assert(!isEqual(getValue(market, "linear"), getValue(market, "inverse")), add("market linear and inverse must not be the same", logText));
                if (!isTrue((inOp(skippedProperties, "contractSize"))))
                {
                    // contract size should be defined
                    assert(!isEqual(contractSize, null), add("\"contractSize\" must be defined when \"contract\" is true", logText));
                    // contract size should be above zero
                    assert(Precise.stringGt(contractSize, "0"), add("\"contractSize\" must be > 0 when \"contract\" is true", logText));
                }
                if (!isTrue((inOp(skippedProperties, "settle"))))
                {
                    // settle should be defined
                    assert(isTrue((!isEqual(getValue(market, "settle"), null))) && isTrue((!isEqual(getValue(market, "settleId"), null))), add("\"settle\" & \"settleId\" must be defined when \"contract\" is true", logText));
                }
            }
            // spot should be false
            assert(!isTrue(getValue(market, "spot")), add("\"spot\" must be false when \"contract\" is true", logText));
        } else
        {
            // linear & inverse needs to be undefined
            assert(isTrue((isEqual(getValue(market, "linear"), null))) && isTrue((isEqual(getValue(market, "inverse"), null))), add("market linear and inverse must be undefined when \"contract\" is false", logText));
            // contract size should be undefined
            if (!isTrue((inOp(skippedProperties, "contractSize"))))
            {
                assert(isEqual(contractSize, null), add("\"contractSize\" must be undefined when \"contract\" is false", logText));
            }
            // settle should be undefined
            assert(isTrue((isEqual(getValue(market, "settle"), null))) && isTrue((isEqual(getValue(market, "settleId"), null))), add("\"settle\" must be undefined when \"contract\" is false", logText));
            // spot should be true
            assert(getValue(market, "spot"), add("\"spot\" must be true when \"contract\" is false", logText));
        }
        // option fields
        if (isTrue(getValue(market, "option")))
        {
            // if option, then strike and optionType should be defined
            assert(!isEqual(getValue(market, "strike"), null), add("\"strike\" must be defined when \"option\" is true", logText));
            assert(!isEqual(getValue(market, "optionType"), null), add("\"optionType\" must be defined when \"option\" is true", logText));
        } else
        {
            // if not option, then strike and optionType should be undefined
            assert(isEqual(getValue(market, "strike"), null), add("\"strike\" must be undefined when \"option\" is false", logText));
            assert(isEqual(getValue(market, "optionType"), null), add("\"optionType\" must be undefined when \"option\" is false", logText));
        }
        // future, swap and option should be mutually exclusive
        if (isTrue(getValue(market, "future")))
        {
            assert(!isTrue(getValue(market, "swap")) && !isTrue(getValue(market, "option")), add("market swap and option must be false when \"future\" is true", logText));
        } else if (isTrue(getValue(market, "swap")))
        {
            assert(!isTrue(getValue(market, "future")) && !isTrue(getValue(market, "option")), add("market future and option must be false when \"swap\" is true", logText));
        } else if (isTrue(getValue(market, "option")))
        {
            assert(!isTrue(getValue(market, "future")) && !isTrue(getValue(market, "swap")), add("market future and swap must be false when \"option\" is true", logText));
        }
        // expiry field
        if (isTrue(isTrue(getValue(market, "future")) || isTrue(getValue(market, "option"))))
        {
            // future or option markets need 'expiry' and 'expiryDatetime'
            assert(!isEqual(getValue(market, "expiry"), null), add("\"expiry\" must be defined when \"future\" is true", logText));
            assert(!isEqual(getValue(market, "expiryDatetime"), null), add("\"expiryDatetime\" must be defined when \"future\" is true", logText));
            // expiry datetime should be correct
            object isoString = exchange.iso8601(getValue(market, "expiry"));
            assert(isEqual(getValue(market, "expiryDatetime"), isoString), add(add(add(add(add("expiryDatetime (\"", getValue(market, "expiryDatetime")), "\") must be equal to expiry in iso8601 format \""), isoString), "\""), logText));
        } else
        {
            // otherwise, they need to be undefined
            assert(isTrue((isEqual(getValue(market, "expiry"), null))) && isTrue((isEqual(getValue(market, "expiryDatetime"), null))), add("\"expiry\" and \"expiryDatetime\" must be undefined when it is not future|option market", logText));
        }
        // check precisions
        if (!isTrue((inOp(skippedProperties, "precision"))))
        {
            object precisionKeys = new List<object>(((IDictionary<string,object>)getValue(market, "precision")).Keys);
            object keysLength = getArrayLength(precisionKeys);
            assert(isGreaterThanOrEqual(keysLength, 2), add("precision should have \"amount\" and \"price\" keys at least", logText));
            for (object i = 0; isLessThan(i, getArrayLength(precisionKeys)); postFixIncrement(ref i))
            {
                testSharedMethods.checkPrecisionAccuracy(exchange, skippedProperties, method, getValue(market, "precision"), getValue(precisionKeys, i));
            }
        }
        // check limits
        if (!isTrue((inOp(skippedProperties, "limits"))))
        {
            object limitsKeys = new List<object>(((IDictionary<string,object>)getValue(market, "limits")).Keys);
            object keysLength = getArrayLength(limitsKeys);
            assert(isGreaterThanOrEqual(keysLength, 3), add("limits should have \"amount\", \"price\" and \"cost\" keys at least", logText));
            for (object i = 0; isLessThan(i, getArrayLength(limitsKeys)); postFixIncrement(ref i))
            {
                object key = getValue(limitsKeys, i);
                object limitEntry = getValue(getValue(market, "limits"), key);
                // min >= 0
                testSharedMethods.assertGreaterOrEqual(exchange, skippedProperties, method, limitEntry, "min", "0");
                // max >= 0
                testSharedMethods.assertGreater(exchange, skippedProperties, method, limitEntry, "max", "0");
                // max >= min
                object minString = exchange.safeString(limitEntry, "min");
                if (isTrue(!isEqual(minString, null)))
                {
                    testSharedMethods.assertGreaterOrEqual(exchange, skippedProperties, method, limitEntry, "max", minString);
                }
            }
        }
        // check whether valid currency ID and CODE is used
        if (!isTrue((inOp(skippedProperties, "currencyIdAndCode"))))
        {
            testSharedMethods.assertValidCurrencyIdAndCode(exchange, skippedProperties, method, market, getValue(market, "baseId"), getValue(market, "base"));
            testSharedMethods.assertValidCurrencyIdAndCode(exchange, skippedProperties, method, market, getValue(market, "quoteId"), getValue(market, "quote"));
            testSharedMethods.assertValidCurrencyIdAndCode(exchange, skippedProperties, method, market, getValue(market, "settleId"), getValue(market, "settle"));
        }
        testSharedMethods.assertTimestamp(exchange, skippedProperties, method, market, null, "created");
    }

}