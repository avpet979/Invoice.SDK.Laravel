<?php
namespace invoice\payment\sdk;

use Illuminate\Support\Facades\Http;

class RestClient
{
    public $url = "https://api.invoice.su/api/v2/";

    public $login;
    public $apiKey;

    /**
     * RestClient constructor.
     * @param $login
     * @param $apiKey
     */
    public function __construct($login, $apiKey)
    {
        $this->login = $login;
        $this->apiKey = $apiKey;
    }

    /**
     * @param $request_type
     * @param $json
     * @return bool|string
     */
    private function Send($request_type, $json)
    {
        $request = $this->url . $request_type;
        $auth = base64_encode($this->login . ":" . $this->apiKey);

        $response = Http::acceptJson()->withHeaders([
            'Authorization' => 'Basic '.$auth,
        ])
            ->post($request, $json);

        if ($response->successful())
            return $response->json();
    }

    /**
     * @param GET_TERMINAL $request
     * @return TerminalInfo
     */
    public function GetTerminal(GET_TERMINAL $request)
    {
        return $this->Send("GetTerminal", get_object_vars($request));
    }

    /**
     * @param CREATE_TERMINAL $request
     * @return TerminalInfo
     */
    public function CreateTerminal(CREATE_TERMINAL $request)
    {
        return $this->Send("CreateTerminal", get_object_vars($request));
    }

    /**
     * @param GET_REFUND $request
     * @return RefundInfo
     */
    public function GetRefund(GET_REFUND $request)
    {
        return $this->Send("GetRefund", get_object_vars($request));
    }

    /**
     * @param CREATE_REFUND $request
     * @return RefundInfo
     */
    public function CreateRefund(CREATE_REFUND $request)
    {
        return $this->Send("CreateRefund", get_object_vars($request));
    }

    /**
     * @param CLOSE_PAYMENT $request
     * @return PaymentInfo
     */
    public function ClosePayment(CLOSE_PAYMENT $request)
    {
        return $this->Send("ClosePayment", get_object_vars($request));
    }

    /**
     * @param GET_PAYMENT_BY_ORDER $request
     * @return PaymentInfo
     */
    public function GetPaymentByOrder(GET_PAYMENT_BY_ORDER $request)
    {
        return $this->Send("GetPaymentByOrder", get_object_vars($request));
    }

    /**
     * @param GET_PAYMENT $request
     * @return PaymentInfo
     */
    public function GetPayment(GET_PAYMENT $request)
    {
        return $this->Send("GetPayment", get_object_vars($request));
    }

    /**
     * @param CREATE_PAYMENT $request
     * @return PaymentInfo
     */
    public function CreatePayment(CREATE_PAYMENT $request)
    {
        return $this->Send("CreatePayment", get_object_vars($request));
    }
}
