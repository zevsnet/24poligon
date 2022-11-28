<?php

namespace SB\Korona\Type;

use Phpro\SoapClient\Type\RequestInterface;

class Authentication implements RequestInterface
{

    /**
     * @var \SB\Korona\Type\PurchaseId
     */
    private $purchaseId;

    /**
     * @var \SB\Korona\Type\Token
     */
    private $token;

    /**
     * @return \SB\Korona\Type\PurchaseId
     */
    public function getPurchaseId()
    {
        return $this->purchaseId;
    }

    /**
     * @param \SB\Korona\Type\PurchaseId $purchaseId
     * @return Authentication
     */
    public function withPurchaseId($purchaseId)
    {
        $new = clone $this;
        $new->purchaseId = $purchaseId;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param \SB\Korona\Type\Token $token
     * @return Authentication
     */
    public function withToken($token)
    {
        $new = clone $this;
        $new->token = $token;

        return $new;
    }


}

