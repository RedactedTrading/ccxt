<?php
namespace ccxt;
use \ccxt\Precise;
use React\Async;
use React\Promise;

// ----------------------------------------------------------------------------

// PLEASE DO NOT EDIT THIS FILE, IT IS GENERATED AND WILL BE OVERWRITTEN:
// https://github.com/ccxt/ccxt/blob/master/CONTRIBUTING.md#how-to-contribute-code

// -----------------------------------------------------------------------------
include_once __DIR__ . '/../base/test_open_interest.php';

function test_fetch_open_interest_history($exchange, $skipped_properties, $symbol) {
    return Async\async(function () use ($exchange, $skipped_properties, $symbol) {
        $method = 'fetchOpenInterestHistory';
        $open_interest_history = Async\await($exchange->fetch_open_interest_history($symbol));
        assert(gettype($open_interest_history) === 'array' && array_keys($open_interest_history) === array_keys(array_keys($open_interest_history)), $exchange->id . ' ' . $method . ' must return an array, returned ' . $exchange->json($open_interest_history));
        for ($i = 0; $i < count($open_interest_history); $i++) {
            test_open_interest($exchange, $skipped_properties, $method, $open_interest_history[$i]);
        }
    }) ();
}