<?php

namespace SB\Korona;

use SB\Korona\Type;
use Phpro\SoapClient\Soap\ClassMap\ClassMapCollection;
use Phpro\SoapClient\Soap\ClassMap\ClassMap;

class CFTLoyaltyPCPointsClassmap
{

    public static function getCollection() : \Phpro\SoapClient\Soap\ClassMap\ClassMapCollection
    {
        return new ClassMapCollection([
            new ClassMap('ResponseStatus', Type\ResponseStatus::class),
            new ClassMap('CardInfoItem', Type\CardInfoItem::class),
            new ClassMap('CardInfo', Type\CardInfo::class),
            new ClassMap('FmtCardInfoItem', Type\FmtCardInfoItem::class),
            new ClassMap('FmtCardInfo', Type\FmtCardInfo::class),
            new ClassMap('CardholderInfoItem', Type\CardholderInfoItem::class),
            new ClassMap('CardholderInfoSeq', Type\CardholderInfoSeq::class),
            new ClassMap('FormOptionsItem', Type\FormOptionsItem::class),
            new ClassMap('FormOptionsSeq', Type\FormOptionsSeq::class),
            new ClassMap('OperSummaryItem', Type\OperSummaryItem::class),
            new ClassMap('OperSummarySeq', Type\OperSummarySeq::class),
            new ClassMap('AccStatementItem', Type\AccStatementItem::class),
            new ClassMap('AccStatementSeq', Type\AccStatementSeq::class),
            new ClassMap('EventItem', Type\EventItem::class),
            new ClassMap('EventSeq', Type\EventSeq::class),
            new ClassMap('AccStatementParams', Type\AccStatementParams::class),
            new ClassMap('ExtParam', Type\ExtParam::class),
            new ClassMap('ExtParamSeq', Type\ExtParamSeq::class),
            new ClassMap('Extension', Type\Extension::class),
            new ClassMap('ExtensionSeq', Type\ExtensionSeq::class),
            new ClassMap('Authentication', Type\Authentication::class),
            new ClassMap('TransactionData', Type\TransactionData::class),
            new ClassMap('LinkRequestData', Type\LinkRequestData::class),
            new ClassMap('PaymentItem', Type\PaymentItem::class),
            new ClassMap('Payment', Type\Payment::class),
            new ClassMap('ChequeItemAttr', Type\ChequeItemAttr::class),
            new ClassMap('ChequeItemAttrSeq', Type\ChequeItemAttrSeq::class),
            new ClassMap('ChequeItem', Type\ChequeItem::class),
            new ClassMap('Cheque', Type\Cheque::class),
            new ClassMap('AllocChequeItem', Type\AllocChequeItem::class),
            new ClassMap('AllocCheque', Type\AllocCheque::class),
            new ClassMap('PointsAllocation', Type\PointsAllocation::class),
            new ClassMap('AuthRequestData', Type\AuthRequestData::class),
            new ClassMap('DirectRequestData', Type\DirectRequestData::class),
            new ClassMap('InfoRequestData', Type\InfoRequestData::class),
            new ClassMap('CardholderRequestData', Type\CardholderRequestData::class),
            new ClassMap('AuthResponseData', Type\AuthResponseData::class),
            new ClassMap('BatchRequestItem', Type\BatchRequestItem::class),
            new ClassMap('BatchRequestSequence', Type\BatchRequestSequence::class),
            new ClassMap('BatchRequestData', Type\BatchRequestData::class),
            new ClassMap('RefundRequestData', Type\RefundRequestData::class),
            new ClassMap('RefundResponseData', Type\RefundResponseData::class),
            new ClassMap('TokenResponseData', Type\TokenResponseData::class),
            new ClassMap('TokenRequiredResponseData', Type\TokenRequiredResponseData::class),
            new ClassMap('InfoDealsResponseData', Type\InfoDealsResponseData::class),
            new ClassMap('Deal', Type\Deal::class),
            new ClassMap('IssuedDeal', Type\IssuedDeal::class),
            new ClassMap('DealSeq', Type\DealSeq::class),
            new ClassMap('DealType', Type\DealType::class),
            new ClassMap('DealTypeSeq', Type\DealTypeSeq::class),
            new ClassMap('ConditionRule', Type\ConditionRule::class),
            new ClassMap('ConditionRuleSeq', Type\ConditionRuleSeq::class),
            new ClassMap('DiscountRule', Type\DiscountRule::class),
            new ClassMap('DiscountRuleSeq', Type\DiscountRuleSeq::class),
            new ClassMap('CalculationParams', Type\CalculationParams::class),
            new ClassMap('ProductGroup', Type\ProductGroup::class),
            new ClassMap('String50Seq', Type\String50Seq::class),
            new ClassMap('ProductClassifier', Type\ProductClassifier::class),
            new ClassMap('ProductClassifierSeq', Type\ProductClassifierSeq::class),
            new ClassMap('AuthDealsRequestData', Type\AuthDealsRequestData::class),
            new ClassMap('IssueDealRequestData', Type\IssueDealRequestData::class),
            new ClassMap('IssueDealResponseData', Type\IssueDealResponseData::class),
            new ClassMap('PreCalculatedBns', Type\PreCalculatedBns::class),
            new ClassMap('CashBackOper', Type\CashBackOper::class),
            new ClassMap('CashBackOperSeq', Type\CashBackOperSeq::class),
            new ClassMap('InfoCashBackResponseData', Type\InfoCashBackResponseData::class),
            new ClassMap('AuthCashBackOper', Type\AuthCashBackOper::class),
            new ClassMap('AuthCashBackOperSeq', Type\AuthCashBackOperSeq::class),
            new ClassMap('BnsActiveRestrictInfo', Type\BnsActiveRestrictInfo::class),
            new ClassMap('item', Type\Item::class),
            new ClassMap('IssueCardRequestData', Type\IssueCardRequestData::class),
            new ClassMap('IssueCardResponseData', Type\IssueCardResponseData::class),
            new ClassMap('SendTokenResponse', Type\SendTokenResponse::class),
            new ClassMap('PreAuthRequest', Type\PreAuthRequest::class),
            new ClassMap('PreAuthResponse', Type\PreAuthResponse::class),
        ]);
    }


}

