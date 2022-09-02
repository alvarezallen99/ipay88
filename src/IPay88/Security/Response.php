<?php

namespace IPay88\Security;

class Response
{
    const ENV_IPAY88_URL = 'IPAY88_URL';
	private $merchantKey;
	public static $validReferrer = "sandbox.ipay88.com.ph";

    public function __construct($merchantKey)
    {
        $this->merchantKey = $merchantKey;
        if (getenv(self::ENV_IPAY88_URL)) {
            $this->validReferrer = getenv(self::ENV_IPAY88_URL);
        }
    }

    public function validate($response)
    {
        if($response->getReferrer() !== self::$validReferrer)
        {
        	throw new Exceptions\InvalidReferrerException;
        }

        $sig = Signature::generateSignature(
        	$this->merchantKey,
        	$response->getMerchantCode(),
        	$response->getPaymentId(),
        	$response->getRefNo(),
        	preg_replace('/[\.\,]/', '', $response->getAmount()), //clear ',' and '.'
        	$response->getCurrency(),
        	$response->getStatus()
        	);

        if($response->getSignature() !== $sig)
        {
        	throw new Exceptions\InvalidSignatureException;
        }

        return true;
    }
}
