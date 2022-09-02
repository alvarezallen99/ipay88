# IPay88 Philippines
[![Build Status](https://img.shields.io/packagist/dt/karyamedia/ipay88.svg?maxAge=2592000)](https://packagist.org/packages/karyamedia/ipay88) [![Join the chat at https://gitter.im/karyamedia/ipay88](https://badges.gitter.im/karyamedia/ipay88.svg)](https://gitter.im/karyamedia/ipay88?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

Ipay88 Philippines Payment Gateway Module.

**NOTE**: Your require to request demo account from techsupport@ipay88.com.my

## Installation

I've make this project available to install via [Composer](https://getcomposer.org/) with following command:

```bash
composer require alvarezallen99/ipay88 dev-master
```

## env
```bash
IPAY88_URL="sandbox.ipay88.com.ph"
IPAY88_MERCHANT_CODE="IPAY88_MERCHANT_CODE"
IPAY88_MERCHANT_KEY="IPAY88_MERCHANT_KEY"
```

## Example Controller

```php
<?php

class Payment {

	protected $merchantCode;
	protected $merchantKey;
    	protected $payment_response;
    	protected $backend_response;

	public function __construct()
	{
		parent::__construct();
		$this->merchantCode = env('IPAY88_MERCHANT_CODE'); //MerchantCode confidential
		$this->merchantKey = env('IPAY88_MERCHANT_KEY'); //MerchantKey confidential
		$this->payment_response = 'http://example.com/response';
        	$this->backend_response = 'http://example.com/backend';
	}

	public function index()
	{
		$request = new IPay88\Payment\Request($this->merchantKey);
		$this->_data = array(
			'merchantCode' => $request->setMerchantCode($this->merchantCode),
			'paymentId' =>  $request->setPaymentId(1),
			'refNo' => $request->setRefNo('EXAMPLE0001'),
			'amount' => $request->setAmount('0.50'),
			'currency' => $request->setCurrency('MYR'),
			'prodDesc' => $request->setProdDesc('Testing'),
			'userName' => $request->setUserName('Your name'),
			'userEmail' => $request->setUserEmail('email@example.com'),
			'userContact' => $request->setUserContact('0123456789'),
			'remark' => $request->setRemark('Some remarks here..'),
			'lang' => $request->setLang('UTF-8'),
			'signature' => $request->getSignature(),
			'responseUrl' => $request->setResponseUrl($this->payment_response),
			'backendUrl' => $request->setBackendUrl($this->backend_response)
			);

		IPay88\Payment\Request::make($this->merchantKey, $this->_data);
	}

	public function response()
	{
		$response = (new IPay88\Payment\Response)->init($this->merchantCode);
		echo "<pre>";
		print_r($response);
	}
}
```

## Credits

[Leow Kah Thong](https://github.com/ktleow)

[Fikri Marhan](https://github.com/fikri-marhan)

[Pijoe](https://github.com/pijoe86)

[aa6my](https://github.com/aa6my)

## Reference
https://github.com/cchitsiang/ipay88

https://github.com/fastsafety/ipay88

## Lisence

MIT © [Karyamedia](https://github.com/karyamedia/karya). Please see [License File](LICENSE.md) for more information.
