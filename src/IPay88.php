<?php

use IPay88\RequestForm;

class IPay88
{
    private $merchantKey = null;
    public $merchantCode = null;
    public $responseUrl = null;
    public $backendResponseUrl = null;
    public $envSuffix = null;

    public function __construct($merchantKey, $merchantCode, $responseUrl, $backendResponseUrl, $envSuffix)
    {
        $this->merchantKey = $merchantKey;
        $this->merchantCode = $merchantCode;
        $this->responseUrl = $responseUrl;
        $this->backendResponseUrl = $backendResponseUrl;
        $this->envSuffix = $envSuffix;
    }

    /**
     * Generate signature to be used for transaction.
     *
     * You may verify your signature with online tool provided by iPay88
     * http://payment.ipay88.com.ph/epayment/testing/TestSignature.asp
     *
     * @access public
     * @param string $merchantKey ProvidedbyiPay88OPSGandsharebetweeniPay88and merchant only
     * @param string $merchantCode Merchant Code provided by iPay88 and use to uniquely identify the Merchant.
     * @param string $refNo Unique merchant transaction id
     * @param int $amount Payment amount
     * @param string $currency Payment currency
     */
    public function generateSignature($refNo, $amount, $currency)
    {
        $stringToHash = $this->merchantKey.$this->merchantCode.$refNo.$amount.$currency;
        return base64_encode(self::_hex2bin(sha1($stringToHash)));
    }

    /**
    *
    * equivalent of php 5.4 hex2bin
    *
    * @access private
    * @param string $source The string to be converted
    */
    private function _hex2bin($source)
    {
        $bin = null;
        for ($i = 0; $i < strlen($source); $i = $i + 2) {
            $bin .= chr(hexdec(substr($source, $i, 2)));
        }
        return $bin;
    }

    /**
    * @access public
    * @param boolean $multiCurrency Set to true to get payments options for multi currency gateway
    */
    public static function getPaymentOptions($multiCurrency = false)
    {
        $phpOnly = array(
            1 => array('Credit Card','PHP'),
            3 => array('GCash','PHP'),
            5 => array('Bancnet','PHP'),
            6 => array('Paypal','PHP'),
            18 => array('DragonPay Online','PHP'),
            19 => array('DragonPay OTC Non-Bank','PHP'),
            20 => array('DragonPay OTC Bank','PHP'),
            22 => array('Pay4Me','PHP'),
            25 => array('Credit Card Pre-Auth','PHP'),
            33 => array('WeChat Scan','PHP'),
            34 => array('WeChat QR', 'PHP'),
            35 => array('Alipay QR', 'PHP'),
            36 => array('Alipay Scan', 'PHP'),
            37 => array('BDO Installment', 'PHP'),
            38 => array('GrabPay', 'PHP'),
            48 => array('7/11', 'PHP'),
            );

        $multiCurrency = array(
            7 => array('Credit Card', 'USD'),
            35 => array('Credit Card','GBP'),
            36 => array('Credit Card','THB'),
            37 => array('Credit Card','CAD'),
            38 => array('Credit Card','SGD'),
            39 => array('Credit Card','AUD'),
            40 => array('Credit Card','MYR'),
            41 => array('Credit Card','EUR'),
            42 => array('Credit Card','HKD'),
            );

        return $multiCurrency ? $multiCurrency : $phpOnly;
    }

    /**
    * @access public
    * @param
    */
    public function makeRequestForm($args)
    {
        $args['merchantCode'] = $this->merchantCode;
        $args['signature'] = $this->generateSignature(
            $args['refNo'],
            (int) $args['amount'],
            $args['currency']
        );
        $args['responseUrl'] = $this->responseUrl;
        $args['backendUrl'] = $this->backendResponseUrl;

        return new IPay88\RequestForm($args);
    }
}
