<?php

namespace IPay88\Payment;

class Response
{
    public const ENV_IPAY88_URL = 'ENV_IPAY88_URL';
    public static $requeryUrl = 'https://sandbox.ipay88.com.ph/epayment/enquiry.asp';
    public static $envSuffix = '';

    public function __construct()
    {
        if (getenv(self::ENV_IPAY88_URL)) {
            self::$requeryUrl = 'https://'.getenv(self::ENV_IPAY88_URL).'/epayment/enquiry.asp';
        }
    }

    private $return;

    public function setEnvSuffix($val)
    {
        self::$envSuffix = $val;
        self::$requeryUrl = 'https://'.getenv(self::ENV_IPAY88_URL.self::$envSuffix).'/epayment/enquiry.asp';
    }

    public static function getEnvSuffix()
    {
        return self::$envSuffix;
    }

    public function init($merchantCode, $requery = true, $return_data = true)
    {
        $return = [
            'status' => '',
            'message' => '',
            'data' => [],
        ];

        $data = $_POST;
        $return['status'] = isset($data['Status']) ? $data['Status'] : false;
        $return['message'] = isset($data['ErrDesc']) ? $data['ErrDesc'] : '';

        if ($requery) {
            if ($return['status']) {
                $data['_RequeryStatus'] = $this->requery($data);
                if ($data['_RequeryStatus'] != '00') {
                    // Requery failed, return NULL array.
                    $return['status'] = false;

                    return $return;
                }
            }
        }

        if ($return_data) {
            $return['data'] = $data;
        }

        return $return;
    }

    /**
     * Check payment status (re-query).
     *
     * @param array $payment_details The following variables are required:
     *                               - MerchantCode
     *                               - RefNo
     *                               - Amount
     *
     * @return string Possible payment status from iPay88 server:
     *                - 00                 - Successful payment
     *                - Invalid parameters - Parameters passed is incorrect
     *                - Record not found   - Could not find the record.
     *                - Incorrect amount   - Amount differs.
     *                - Payment fail       - Payment failed.
     *                - M88Admin           - Payment status updated by Mobile88 Admin (Fail)
     */
    public function requery($payment_details)
    {
        if (!function_exists('curl_init')) {
            trigger_error('PHP cURL extension is required.');

            return false;
        }
        $curl = curl_init(self::$requeryUrl.'?'.http_build_query($payment_details));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = trim(curl_exec($curl));
        //$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return $result;
    }
}
