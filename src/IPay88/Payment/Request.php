<?php

namespace IPay88\Payment;

use IPay88\Security\Signature;
use IPay88\View\RequestForm;

class Request
{
	const ENV_IPAY88_URL = 'IPAY88_URL';
	const DEFAULT_IPAY88_URL = 'https://sandbox.ipay88.com.ph/epayment/entry.asp';

	public static $paymentUrl;

	private $merchantKey;

	public function __construct($merchantKey)
	{
		$this->merchantKey = $merchantKey;
		$this->paymentUrl = self::DEFAULT_IPAY88_URL;
		if(getenv(self::ENV_IPAY88_URL)) {
			$this->paymentUrl = 'https://' . getenv(self::ENV_IPAY88_URL) . '/epayment/entry.asp';
		}
	}

	private $merchantCode;
	public function getMerchantCode()
	{
		return $this->merchantCode;
	}
	public function setMerchantCode($val)
	{
		$this->signature = null; //need new signature if this is changed
		return $this->merchantCode = $val;
	}

	private $paymentId;
	public function getPaymentId()
	{
		return $this->paymentId;
	}
	public function setPaymentId($val)
	{
		return $this->paymentId = $val;
	}

	private $refNo;
	public function getRefNo()
	{
		return $this->refNo;
	}

	public function setRefNo($val)
	{
		$this->signature = null; //need new signature if this is changed
		return $this->refNo = $val;
	}

	private $amount;
	public function getAmount()
	{
		return $this->amount;
	}

	public function setAmount($val)
	{
		$this->signature = null; //need new signature if this is changed
		return $this->amount = $val;
	}

	private $currency;
	public function getCurrency()
	{
		return $this->currency;
	}

	public function setCurrency($val)
	{
		$this->signature = null; //need new signature if this is changed
		return $this->currency = $val;
	}

	private $prodDesc;
	public function getProdDesc()
	{
		return $this->prodDesc;
	}
	public function setProdDesc($val)
	{
		return $this->prodDesc = $val;
	}

	private $userName;
	public function getUserName()
	{
		return $this->userName;
	}
	public function setUserName($val)
	{
		return $this->userName = $val;
	}

	private $userEmail;
	public function getUserEmail()
	{
		return $this->userEmail;
	}
	public function setUserEmail($val)
	{
		return $this->userEmail = $val;
	}

	private $userContact;
	public function getUserContact()
	{
		return $this->userContact;
	}

	public function setUserContact($val)
	{
		return $this->userContact = $val;
	}

	private $remark;
	public function getRemark()
	{
		return $this->remark;
	}

	public function setRemark($val)
	{
		return $this->remark = $val;
	}

	private $lang;
	public function getLang()
	{
		return $this->lang;
	}
	public function setLang($val)
	{
		return $this->lang = $val;
	}

	private $signature;
	public function getSignature($refresh = false)
	{
		//simple caching
		if((!$this->signature) || $refresh)
		{
			$this->signature = Signature::generateSignature(
				$this->merchantKey,
				$this->getMerchantCode(),
				$this->getRefNo(),
				preg_replace('/[\.\,]/', '', $this->getAmount()), //clear ',' and '.'
				$this->getCurrency()
			);
		}

		return $this->signature;
	}

	private $responseUrl;
	public function getResponseUrl()
	{
		return $this->responseUrl;
	}
	public function setResponseUrl($val)
	{
		return $this->responseUrl = $val;
	}

	private $backendUrl;
	public function getBackendUrl()
	{
		return $this->backendUrl;
	}
	public function setBackendUrl($val)
	{
		return $this->backendUrl = $val;
	}

	protected static $fillable_fields = [
		'merchantCode','paymentId','refNo','amount',
		'currency','prodDesc','userName','userEmail',
		'userContact','remark','lang','responseUrl','backendUrl'
	];

	/**
	* IPay88 Payment Request factory function
	*
	* @access public
	* @param string $merchantKey The merchant key provided by ipay88
	* @param hash $fieldValues Set of field value that is to be set as the properties
	*  Override `$fillable_fields` to determine what value can be set during this factory method
	* @example
	*  $request = IPay88\Payment\Request::make($merchantKey, $fieldValues)
	*
	*/
	public static function make($merchantKey, $fieldValues)
	{
		$request = new Request($merchantKey);
		RequestForm::render($fieldValues, self::$paymentUrl);
	}

    /**
    * @access public
    * @param boolean $multiCurrency Set to true to get payments optinos for multi currency gateway
    */
    public static function getPaymentOptions($multiCurrency = true)
    {
		$phpOnly = array(
			2 => array('Credit Card', 'PHP'),
			6 => array('Maybank2U', 'PHP'),
			8 => array('Alliance Online', 'PHP'),
			10 => array('AmOnline', 'PHP'),
			14 => array('RHB Online', 'PHP'),
			15 => array('Hong Leong Online', 'PHP'),
			16 => array('FPX', 'PHP'),
			20 => array('CIMB Click', 'PHP'),
			22 => array('Web Cash', 'PHP'),
			48 => array('PayPal', 'PHP'),
			100 => array('Celcom AirCash', 'PHP'),
			102 => array('Bank Rakyat Internet Banking', 'PHP'),
			103 => array('AffinOnline', 'PHP')
		);

		$multiCurrency = array(
			25 => array('Credit Card', 'USD'),
			35 => array('Credit Card', 'GBP'),
			36 => array('Credit Card', 'THB'),
			37 => array('Credit Card', 'CAD'),
			38 => array('Credit Card', 'SGD'),
			39 => array('Credit Card', 'AUD'),
			40 => array('Credit Card', 'MYR'),
			41 => array('Credit Card', 'EUR'),
			42 => array('Credit Card', 'HKD'),
		);

		return $multiCurrency ? $multiCurrency : $phpOnly;
    }


}
