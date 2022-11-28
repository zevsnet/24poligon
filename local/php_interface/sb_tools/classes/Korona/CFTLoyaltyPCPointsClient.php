<?php

namespace SB\Korona;

use Phpro\SoapClient\Event\FaultEvent;
use Phpro\SoapClient\Event\RequestEvent;
use Phpro\SoapClient\Event\ResponseEvent;
use Phpro\SoapClient\Events;
use Phpro\SoapClient\Type\MixedResult;
use Phpro\SoapClient\Type\MultiArgumentRequestInterface;
use Phpro\SoapClient\Type\ResultProviderInterface;
use SB\Korona\Type;
use Phpro\SoapClient\Type\RequestInterface;
use Phpro\SoapClient\Type\ResultInterface;
use Phpro\SoapClient\Exception\SoapException;
use SB\Site\Korona\KoronaFactory;


class CFTLoyaltyPCPointsClient extends \Phpro\SoapClient\Client
{

    /**
     * @param RequestInterface|Type\AuthRequestData $request
     * @return ResultInterface|Type\AuthResponseData
     * @throws SoapException
     */
    public function activate(\SB\Korona\Type\AuthRequestData $request): \SB\Korona\Type\AuthResponseData
    {
        return $this->call('activate', $request);
    }

    /**
     * @param RequestInterface|Type\LinkRequestData $request
     * @return ResultInterface|Type\AuthResponseData
     * @throws SoapException
     */
    public function linkCard(\SB\Korona\Type\LinkRequestData $request): \SB\Korona\Type\AuthResponseData
    {
        return $this->call('linkCard', $request);
    }

    /**
     * @param RequestInterface|Type\LinkRequestData $request
     * @return ResultInterface|Type\AuthResponseData
     * @throws SoapException
     */
    public function replaceCard(\SB\Korona\Type\LinkRequestData $request): \SB\Korona\Type\AuthResponseData
    {
        return $this->call('replaceCard', $request);
    }

    /**
     * @param RequestInterface|Type\TransactionData $transaction
     * @return ResultInterface|Type\AuthResponseData
     * @throws SoapException
     */
    public function getInfo(\SB\Korona\Type\TransactionData $transaction): \SB\Korona\Type\AuthResponseData
    {
        return $this->call('getInfo', $transaction);
    }

    /**
     * @param RequestInterface|Type\InfoRequestData $request
     * @return ResultInterface|Type\AuthResponseData
     * @throws SoapException
     */
    public function getInfo2(\SB\Korona\Type\InfoRequestData $request)
    {

        $res = $this->call('getInfo2', $request);

        return $res;
    }

    /**
     * @param RequestInterface|Type\AuthRequestData $request
     * @return ResultInterface|Type\AuthResponseData
     * @throws SoapException
     */
    public function authPoints(\SB\Korona\Type\AuthRequestData $request)
    {
        return $this->call('authPoints', $request);
    }

    /**
     * @param RequestInterface|Type\AuthRequestData $request
     * @return ResultInterface|Type\AuthResponseData
     * @throws SoapException
     */
    public function authGift(\SB\Korona\Type\AuthRequestData $request)
    {
        return $this->call('authGift', $request);
    }

    /**
     * @param RequestInterface|Type\TransactionData $transaction
     * @return ResultInterface|Type\ResponseStatus
     * @throws SoapException
     */
    public function reverse(\SB\Korona\Type\TransactionData $transaction): \SB\Korona\Type\ResponseStatus
    {
        return $this->call('reverse', $transaction);
    }

    /**
     * @param RequestInterface|Type\BatchRequestData $request
     * @return ResultInterface|Type\ResponseStatus
     * @throws SoapException
     */
    public function batchLoad(\SB\Korona\Type\BatchRequestData $request): \SB\Korona\Type\ResponseStatus
    {
        return $this->call('batchLoad', $request);
    }

    /**
     * @param RequestInterface|Type\RefundRequestData $request
     * @return ResultInterface|Type\RefundResponseData
     * @throws SoapException
     */
    public function refund(\SB\Korona\Type\RefundRequestData $request): \SB\Korona\Type\RefundResponseData
    {
        return $this->call('refund', $request);
    }

    /**
     * @param RequestInterface|Type\String $request
     * @return ResultInterface|Type\String
     * @throws SoapException
     */
    public function echoCall($request)
    {
        return $this->call('echo', $request);
    }

    /**
     * @param RequestInterface|Type\AuthRequestData $request
     * @return ResultInterface|Type\TokenResponseData
     * @throws SoapException
     */
    public function getAuthToken(\SB\Korona\Type\AuthRequestData $request)
    {
        return $this->call('getAuthToken', $request);
    }

    /**
     * @param RequestInterface|Type\DirectRequestData $request
     * @return ResultInterface|Type\AuthResponseData
     * @throws SoapException
     */
    public function directPoints(\SB\Korona\Type\DirectRequestData $request): \SB\Korona\Type\AuthResponseData
    {
        return $this->call('directPoints', $request);
    }

    /**
     * @param RequestInterface|Type\CardholderRequestData $request
     * @return ResultInterface|Type\AuthResponseData
     * @throws SoapException
     */
    public function setCardholder(\SB\Korona\Type\CardholderRequestData $request): \SB\Korona\Type\AuthResponseData
    {
        return $this->call('setCardholder', $request);
    }

    /**
     * @param RequestInterface|Type\TransactionData $request
     * @return ResultInterface|Type\InfoDealsResponseData
     * @throws SoapException
     */
    public function getInfoDeals(\SB\Korona\Type\TransactionData $request): \SB\Korona\Type\InfoDealsResponseData
    {
        return $this->call('getInfoDeals', $request);
    }

    /**
     * @param RequestInterface|Type\AuthDealsRequestData $request
     * @return ResultInterface|Type\AuthResponseData
     * @throws SoapException
     */
    public function authDeals(\SB\Korona\Type\AuthDealsRequestData $request): \SB\Korona\Type\AuthResponseData
    {
        return $this->call('authDeals', $request);
    }

    /**
     * @param RequestInterface|Type\IssueDealRequestData $request
     * @return ResultInterface|Type\IssueDealResponseData
     * @throws SoapException
     */
    public function issueDeal(\SB\Korona\Type\IssueDealRequestData $request): \SB\Korona\Type\IssueDealResponseData
    {
        return $this->call('issueDeal', $request);
    }

    /**
     * @param RequestInterface|Type\TransactionData $request
     * @return ResultInterface|Type\InfoCashBackResponseData
     * @throws SoapException
     */
    public function getInfoCashBack(\SB\Korona\Type\TransactionData $request): \SB\Korona\Type\InfoCashBackResponseData
    {
        return $this->call('getInfoCashBack', $request);
    }

    /**
     * @param RequestInterface|Type\IssueCardRequestData $request
     * @return ResultInterface|Type\IssueCardResponseData
     * @throws SoapException
     */
    public function issueCard(\SB\Korona\Type\IssueCardRequestData $request)
    {
        return $this->call('issueCard', $request);
    }

    /**
     * @param RequestInterface|Type\AuthRequestData $request
     * @return ResultInterface|Type\TokenRequiredResponseData
     * @throws SoapException
     */
    public function checkTokenRequired(\SB\Korona\Type\AuthRequestData $request)
    {
        return $this->call('checkTokenRequired', $request);
    }

    /**
     * @param RequestInterface|Type\TransactionData $request
     * @return ResultInterface|Type\SendTokenResponse
     * @throws SoapException
     */
    public function sendVerifyToken(\SB\Korona\Type\TransactionData $request)
    {
        return $this->call('sendVerifyToken', $request);
    }

    /**
     * @param RequestInterface|Type\PreAuthRequest $request
     * @return ResultInterface|Type\PreAuthResponse
     * @throws SoapException
     */
    public function preAuthPoints(\SB\Korona\Type\PreAuthRequest $request)
    {
        return $this->call('preAuthPoints', $request);
    }

    /**
     * @param string $method
     * @param RequestInterface $request
     *
     * @return ResultInterface
     * @throws SoapException
     */
    protected function call(string $method, RequestInterface $request): ResultInterface
    {
        $requestEvent = new RequestEvent($this, $method, $request);
        $this->dispatcher->dispatch(Events::REQUEST, $requestEvent);

        try {

            $arguments = ($request instanceof MultiArgumentRequestInterface) ? $request->getArguments() : [$request];
            $argumentsNew = KoronaFactory::obj2array($arguments);

            $result = call_user_func_array([$this->soapClient, $method], $argumentsNew);

            if ($result instanceof ResultProviderInterface) {
                $result = $result->getResult();
            }


            if (!$result instanceof ResultInterface) {
                $result = new MixedResult($result);
            }
        } catch (\Exception $exception) {

            $soapException = SoapException::fromThrowable($exception);

            $this->dispatcher->dispatch(Events::FAULT, new FaultEvent($this, $soapException, $requestEvent));
            throw $soapException;
        }

        $this->dispatcher->dispatch(Events::RESPONSE, new ResponseEvent($this, $requestEvent, $result));
        return $result;
    }


}

