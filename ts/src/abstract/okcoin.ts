// -------------------------------------------------------------------------------

// PLEASE DO NOT EDIT THIS FILE, IT IS GENERATED AND WILL BE OVERWRITTEN:
// https://github.com/ccxt/ccxt/blob/master/CONTRIBUTING.md#how-to-contribute-code

// -------------------------------------------------------------------------------

import { implicitReturnType } from '../base/types.js';
import { Exchange as _Exchange } from '../base/Exchange.js';

interface Exchange {
    generalGetTime (params?: {}): Promise<implicitReturnType>;
    accountGetWallet (params?: {}): Promise<implicitReturnType>;
    accountGetSubAccount (params?: {}): Promise<implicitReturnType>;
    accountGetAssetValuation (params?: {}): Promise<implicitReturnType>;
    accountGetWalletCurrency (params?: {}): Promise<implicitReturnType>;
    accountGetWithdrawalHistory (params?: {}): Promise<implicitReturnType>;
    accountGetWithdrawalHistoryCurrency (params?: {}): Promise<implicitReturnType>;
    accountGetLedger (params?: {}): Promise<implicitReturnType>;
    accountGetDepositAddress (params?: {}): Promise<implicitReturnType>;
    accountGetDepositHistory (params?: {}): Promise<implicitReturnType>;
    accountGetDepositHistoryCurrency (params?: {}): Promise<implicitReturnType>;
    accountGetCurrencies (params?: {}): Promise<implicitReturnType>;
    accountGetWithdrawalFee (params?: {}): Promise<implicitReturnType>;
    accountGetDepositLightning (params?: {}): Promise<implicitReturnType>;
    accountGetWithdrawalLightning (params?: {}): Promise<implicitReturnType>;
    accountGetFiatDepositDetail (params?: {}): Promise<implicitReturnType>;
    accountGetFiatDepositDetails (params?: {}): Promise<implicitReturnType>;
    accountGetFiatWithdrawDetail (params?: {}): Promise<implicitReturnType>;
    accountGetFiatWithdrawDetails (params?: {}): Promise<implicitReturnType>;
    accountGetFiatChannel (params?: {}): Promise<implicitReturnType>;
    accountPostTransfer (params?: {}): Promise<implicitReturnType>;
    accountPostWithdrawal (params?: {}): Promise<implicitReturnType>;
    accountPostFiatCancelDeposit (params?: {}): Promise<implicitReturnType>;
    accountPostFiatDeposit (params?: {}): Promise<implicitReturnType>;
    accountPostFiatWithdraw (params?: {}): Promise<implicitReturnType>;
    accountPostFiatCancelWithdrawal (params?: {}): Promise<implicitReturnType>;
    otcGetRfqInstruments (params?: {}): Promise<implicitReturnType>;
    otcGetRfqTrade (params?: {}): Promise<implicitReturnType>;
    otcGetRfqHistory (params?: {}): Promise<implicitReturnType>;
    otcPostRfqQuote (params?: {}): Promise<implicitReturnType>;
    otcPostRfqTrade (params?: {}): Promise<implicitReturnType>;
    usersGetSubaccountInfo (params?: {}): Promise<implicitReturnType>;
    usersGetAccountInfo (params?: {}): Promise<implicitReturnType>;
    usersGetSubaccountApikey (params?: {}): Promise<implicitReturnType>;
    usersPostCreateSubaccount (params?: {}): Promise<implicitReturnType>;
    usersPostDeleteSubaccount (params?: {}): Promise<implicitReturnType>;
    usersPostSubaccountApikey (params?: {}): Promise<implicitReturnType>;
    usersPostSubacountDeleteApikey (params?: {}): Promise<implicitReturnType>;
    usersPostSubacountModifyApikey (params?: {}): Promise<implicitReturnType>;
    earningGetOffers (params?: {}): Promise<implicitReturnType>;
    earningGetOrders (params?: {}): Promise<implicitReturnType>;
    earningGetPositions (params?: {}): Promise<implicitReturnType>;
    earningPostPurchase (params?: {}): Promise<implicitReturnType>;
    earningPostRedeem (params?: {}): Promise<implicitReturnType>;
    earningPostCancel (params?: {}): Promise<implicitReturnType>;
    spotGetAccounts (params?: {}): Promise<implicitReturnType>;
    spotGetAccountsCurrency (params?: {}): Promise<implicitReturnType>;
    spotGetAccountsCurrencyLedger (params?: {}): Promise<implicitReturnType>;
    spotGetOrders (params?: {}): Promise<implicitReturnType>;
    spotGetOrdersPending (params?: {}): Promise<implicitReturnType>;
    spotGetOrdersOrderId (params?: {}): Promise<implicitReturnType>;
    spotGetOrdersClientOid (params?: {}): Promise<implicitReturnType>;
    spotGetTradeFee (params?: {}): Promise<implicitReturnType>;
    spotGetFills (params?: {}): Promise<implicitReturnType>;
    spotGetAlgo (params?: {}): Promise<implicitReturnType>;
    spotGetInstruments (params?: {}): Promise<implicitReturnType>;
    spotGetInstrumentsInstrumentIdBook (params?: {}): Promise<implicitReturnType>;
    spotGetInstrumentsTicker (params?: {}): Promise<implicitReturnType>;
    spotGetInstrumentsInstrumentIdTicker (params?: {}): Promise<implicitReturnType>;
    spotGetInstrumentsInstrumentIdTrades (params?: {}): Promise<implicitReturnType>;
    spotGetInstrumentsInstrumentIdCandles (params?: {}): Promise<implicitReturnType>;
    spotPostOrderAlgo (params?: {}): Promise<implicitReturnType>;
    spotPostOrders (params?: {}): Promise<implicitReturnType>;
    spotPostBatchOrders (params?: {}): Promise<implicitReturnType>;
    spotPostCancelOrdersOrderId (params?: {}): Promise<implicitReturnType>;
    spotPostCancelOrdersClientOid (params?: {}): Promise<implicitReturnType>;
    spotPostCancelBatchAlgos (params?: {}): Promise<implicitReturnType>;
    spotPostCancelBatchOrders (params?: {}): Promise<implicitReturnType>;
    spotPostAmendOrderInstrumentId (params?: {}): Promise<implicitReturnType>;
    spotPostAmendBatchOrders (params?: {}): Promise<implicitReturnType>;
    marginGetAccounts (params?: {}): Promise<implicitReturnType>;
    marginGetAccountsInstrumentId (params?: {}): Promise<implicitReturnType>;
    marginGetAccountsInstrumentIdLedger (params?: {}): Promise<implicitReturnType>;
    marginGetAccountsAvailability (params?: {}): Promise<implicitReturnType>;
    marginGetAccountsInstrumentIdAvailability (params?: {}): Promise<implicitReturnType>;
    marginGetAccountsBorrowed (params?: {}): Promise<implicitReturnType>;
    marginGetAccountsInstrumentIdBorrowed (params?: {}): Promise<implicitReturnType>;
    marginGetOrders (params?: {}): Promise<implicitReturnType>;
    marginGetAccountsInstrumentIdLeverage (params?: {}): Promise<implicitReturnType>;
    marginGetOrdersOrderId (params?: {}): Promise<implicitReturnType>;
    marginGetOrdersClientOid (params?: {}): Promise<implicitReturnType>;
    marginGetOrdersPending (params?: {}): Promise<implicitReturnType>;
    marginGetFills (params?: {}): Promise<implicitReturnType>;
    marginGetInstrumentsInstrumentIdMarkPrice (params?: {}): Promise<implicitReturnType>;
    marginPostAccountsBorrow (params?: {}): Promise<implicitReturnType>;
    marginPostAccountsRepayment (params?: {}): Promise<implicitReturnType>;
    marginPostOrders (params?: {}): Promise<implicitReturnType>;
    marginPostBatchOrders (params?: {}): Promise<implicitReturnType>;
    marginPostCancelOrders (params?: {}): Promise<implicitReturnType>;
    marginPostCancelOrdersOrderId (params?: {}): Promise<implicitReturnType>;
    marginPostCancelOrdersClientOid (params?: {}): Promise<implicitReturnType>;
    marginPostCancelBatchOrders (params?: {}): Promise<implicitReturnType>;
    marginPostAmendOrderInstrumentId (params?: {}): Promise<implicitReturnType>;
    marginPostAmendBatchOrders (params?: {}): Promise<implicitReturnType>;
    marginPostAccountsInstrumentIdLeverage (params?: {}): Promise<implicitReturnType>;
    systemGetStatus (params?: {}): Promise<implicitReturnType>;
    marketGetOracle (params?: {}): Promise<implicitReturnType>;
    futuresGetPosition (params?: {}): Promise<implicitReturnType>;
    futuresGetInstrumentIdPosition (params?: {}): Promise<implicitReturnType>;
    futuresGetAccounts (params?: {}): Promise<implicitReturnType>;
    futuresGetAccountsUnderlying (params?: {}): Promise<implicitReturnType>;
    futuresGetAccountsUnderlyingLeverage (params?: {}): Promise<implicitReturnType>;
    futuresGetAccountsUnderlyingLedger (params?: {}): Promise<implicitReturnType>;
    futuresGetOrderAlgoInstrumentId (params?: {}): Promise<implicitReturnType>;
    futuresGetOrdersInstrumentId (params?: {}): Promise<implicitReturnType>;
    futuresGetOrdersInstrumentIdOrderId (params?: {}): Promise<implicitReturnType>;
    futuresGetOrdersInstrumentIdClientOid (params?: {}): Promise<implicitReturnType>;
    futuresGetFills (params?: {}): Promise<implicitReturnType>;
    futuresGetTradeFee (params?: {}): Promise<implicitReturnType>;
    futuresGetAccountsInstrumentIdHolds (params?: {}): Promise<implicitReturnType>;
    futuresGetInstruments (params?: {}): Promise<implicitReturnType>;
    futuresGetInstrumentsInstrumentIdBook (params?: {}): Promise<implicitReturnType>;
    futuresGetInstrumentsTicker (params?: {}): Promise<implicitReturnType>;
    futuresGetInstrumentsInstrumentIdTicker (params?: {}): Promise<implicitReturnType>;
    futuresGetInstrumentsInstrumentIdTrades (params?: {}): Promise<implicitReturnType>;
    futuresGetInstrumentsInstrumentIdCandles (params?: {}): Promise<implicitReturnType>;
    futuresGetInstrumentsInstrumentIdHistoryCandles (params?: {}): Promise<implicitReturnType>;
    futuresGetInstrumentsInstrumentIdIndex (params?: {}): Promise<implicitReturnType>;
    futuresGetRate (params?: {}): Promise<implicitReturnType>;
    futuresGetInstrumentsInstrumentIdEstimatedPrice (params?: {}): Promise<implicitReturnType>;
    futuresGetInstrumentsInstrumentIdOpenInterest (params?: {}): Promise<implicitReturnType>;
    futuresGetInstrumentsInstrumentIdPriceLimit (params?: {}): Promise<implicitReturnType>;
    futuresGetInstrumentsInstrumentIdMarkPrice (params?: {}): Promise<implicitReturnType>;
    futuresGetInstrumentsInstrumentIdLiquidation (params?: {}): Promise<implicitReturnType>;
    futuresPostAccountsUnderlyingLeverage (params?: {}): Promise<implicitReturnType>;
    futuresPostOrder (params?: {}): Promise<implicitReturnType>;
    futuresPostAmendOrderInstrumentId (params?: {}): Promise<implicitReturnType>;
    futuresPostOrders (params?: {}): Promise<implicitReturnType>;
    futuresPostCancelOrderInstrumentIdOrderId (params?: {}): Promise<implicitReturnType>;
    futuresPostCancelOrderInstrumentIdClientOid (params?: {}): Promise<implicitReturnType>;
    futuresPostCancelBatchOrdersInstrumentId (params?: {}): Promise<implicitReturnType>;
    futuresPostAccountsMarginMode (params?: {}): Promise<implicitReturnType>;
    futuresPostClosePosition (params?: {}): Promise<implicitReturnType>;
    futuresPostCancelAll (params?: {}): Promise<implicitReturnType>;
    futuresPostOrderAlgo (params?: {}): Promise<implicitReturnType>;
    futuresPostCancelAlgos (params?: {}): Promise<implicitReturnType>;
    swapGetPosition (params?: {}): Promise<implicitReturnType>;
    swapGetInstrumentIdPosition (params?: {}): Promise<implicitReturnType>;
    swapGetAccounts (params?: {}): Promise<implicitReturnType>;
    swapGetInstrumentIdAccounts (params?: {}): Promise<implicitReturnType>;
    swapGetAccountsInstrumentIdSettings (params?: {}): Promise<implicitReturnType>;
    swapGetAccountsInstrumentIdLedger (params?: {}): Promise<implicitReturnType>;
    swapGetOrdersInstrumentId (params?: {}): Promise<implicitReturnType>;
    swapGetOrdersInstrumentIdOrderId (params?: {}): Promise<implicitReturnType>;
    swapGetOrdersInstrumentIdClientOid (params?: {}): Promise<implicitReturnType>;
    swapGetFills (params?: {}): Promise<implicitReturnType>;
    swapGetAccountsInstrumentIdHolds (params?: {}): Promise<implicitReturnType>;
    swapGetTradeFee (params?: {}): Promise<implicitReturnType>;
    swapGetOrderAlgoInstrumentId (params?: {}): Promise<implicitReturnType>;
    swapGetInstruments (params?: {}): Promise<implicitReturnType>;
    swapGetInstrumentsInstrumentIdDepth (params?: {}): Promise<implicitReturnType>;
    swapGetInstrumentsTicker (params?: {}): Promise<implicitReturnType>;
    swapGetInstrumentsInstrumentIdTicker (params?: {}): Promise<implicitReturnType>;
    swapGetInstrumentsInstrumentIdTrades (params?: {}): Promise<implicitReturnType>;
    swapGetInstrumentsInstrumentIdCandles (params?: {}): Promise<implicitReturnType>;
    swapGetInstrumentsInstrumentIdHistoryCandles (params?: {}): Promise<implicitReturnType>;
    swapGetInstrumentsInstrumentIdIndex (params?: {}): Promise<implicitReturnType>;
    swapGetRate (params?: {}): Promise<implicitReturnType>;
    swapGetInstrumentsInstrumentIdOpenInterest (params?: {}): Promise<implicitReturnType>;
    swapGetInstrumentsInstrumentIdPriceLimit (params?: {}): Promise<implicitReturnType>;
    swapGetInstrumentsInstrumentIdLiquidation (params?: {}): Promise<implicitReturnType>;
    swapGetInstrumentsInstrumentIdFundingTime (params?: {}): Promise<implicitReturnType>;
    swapGetInstrumentsInstrumentIdMarkPrice (params?: {}): Promise<implicitReturnType>;
    swapGetInstrumentsInstrumentIdHistoricalFundingRate (params?: {}): Promise<implicitReturnType>;
    swapPostAccountsInstrumentIdLeverage (params?: {}): Promise<implicitReturnType>;
    swapPostOrder (params?: {}): Promise<implicitReturnType>;
    swapPostAmendOrderInstrumentId (params?: {}): Promise<implicitReturnType>;
    swapPostOrders (params?: {}): Promise<implicitReturnType>;
    swapPostCancelOrderInstrumentIdOrderId (params?: {}): Promise<implicitReturnType>;
    swapPostCancelOrderInstrumentIdClientOid (params?: {}): Promise<implicitReturnType>;
    swapPostCancelBatchOrdersInstrumentId (params?: {}): Promise<implicitReturnType>;
    swapPostOrderAlgo (params?: {}): Promise<implicitReturnType>;
    swapPostCancelAlgos (params?: {}): Promise<implicitReturnType>;
    swapPostClosePosition (params?: {}): Promise<implicitReturnType>;
    swapPostCancelAll (params?: {}): Promise<implicitReturnType>;
    optionGetAccounts (params?: {}): Promise<implicitReturnType>;
    optionGetPosition (params?: {}): Promise<implicitReturnType>;
    optionGetUnderlyingPosition (params?: {}): Promise<implicitReturnType>;
    optionGetAccountsUnderlying (params?: {}): Promise<implicitReturnType>;
    optionGetOrdersUnderlying (params?: {}): Promise<implicitReturnType>;
    optionGetFillsUnderlying (params?: {}): Promise<implicitReturnType>;
    optionGetAccountsUnderlyingLedger (params?: {}): Promise<implicitReturnType>;
    optionGetTradeFee (params?: {}): Promise<implicitReturnType>;
    optionGetOrdersUnderlyingOrderId (params?: {}): Promise<implicitReturnType>;
    optionGetOrdersUnderlyingClientOid (params?: {}): Promise<implicitReturnType>;
    optionGetUnderlying (params?: {}): Promise<implicitReturnType>;
    optionGetInstrumentsUnderlying (params?: {}): Promise<implicitReturnType>;
    optionGetInstrumentsUnderlyingSummary (params?: {}): Promise<implicitReturnType>;
    optionGetInstrumentsUnderlyingSummaryInstrumentId (params?: {}): Promise<implicitReturnType>;
    optionGetInstrumentsInstrumentIdBook (params?: {}): Promise<implicitReturnType>;
    optionGetInstrumentsInstrumentIdTrades (params?: {}): Promise<implicitReturnType>;
    optionGetInstrumentsInstrumentIdTicker (params?: {}): Promise<implicitReturnType>;
    optionGetInstrumentsInstrumentIdCandles (params?: {}): Promise<implicitReturnType>;
    optionPostOrder (params?: {}): Promise<implicitReturnType>;
    optionPostOrders (params?: {}): Promise<implicitReturnType>;
    optionPostCancelOrderUnderlyingOrderId (params?: {}): Promise<implicitReturnType>;
    optionPostCancelOrderUnderlyingClientOid (params?: {}): Promise<implicitReturnType>;
    optionPostCancelBatchOrdersUnderlying (params?: {}): Promise<implicitReturnType>;
    optionPostAmendOrderUnderlying (params?: {}): Promise<implicitReturnType>;
    optionPostAmendBatchOrdersUnderlying (params?: {}): Promise<implicitReturnType>;
    informationGetCurrencyLongShortRatio (params?: {}): Promise<implicitReturnType>;
    informationGetCurrencyVolume (params?: {}): Promise<implicitReturnType>;
    informationGetCurrencyTaker (params?: {}): Promise<implicitReturnType>;
    informationGetCurrencySentiment (params?: {}): Promise<implicitReturnType>;
    informationGetCurrencyMargin (params?: {}): Promise<implicitReturnType>;
    indexGetInstrumentIdConstituents (params?: {}): Promise<implicitReturnType>;
}
abstract class Exchange extends _Exchange {}

export default Exchange