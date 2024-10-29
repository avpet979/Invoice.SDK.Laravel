<?php


namespace invoice\payment;


use invoice\payment\sdk\common\ORDER;
use invoice\payment\sdk\common\REFUND_INFO;
use invoice\payment\sdk\common\SETTINGS;
use invoice\payment\sdk\CREATE_PAYMENT;
use invoice\payment\sdk\CREATE_REFUND;
use invoice\payment\sdk\CREATE_TERMINAL;
use invoice\payment\sdk\GET_PAYMENT_BY_ORDER;
use invoice\payment\sdk\GET_TERMINAL;
use invoice\payment\sdk\RestClient;

class InvoicePaymentManager
{
    /**
     * @var RestClient $restClient
     */
    private $restClient;

    public function __construct()
    {
        $this->restClient = new RestClient(config("invoice.login"), config('invoice.api_key'));
    }

    /**
     * @param string $orderId
     * @param $amount
     * @param array $receipt
     * @param string $fail_url
     * @param string $success_url
     * @return sdk\PaymentInfo
     * @throws \ErrorException
     */
    public function createPayment($orderId, $amount, array $receipt, $fail_url = "", $success_url = "") {
        $tid = $this->getTerminal();
        if($tid == null or empty($tid)) {
            $tid = $this->createTerminal(config("invoice.default_terminal_name"))->id;
        }
        $order = new ORDER($amount, $orderId);

        $settings = new SETTINGS($tid);

        $settings->fail_url = $fail_url;
        $settings->success_url = $success_url;

        $request = new CREATE_PAYMENT($order, $settings, $receipt);

        dd($request);

        $response = $this->restClient->CreatePayment($request);
        if($response == null) throw new \ErrorException("Payment not created!");
        if(isset($response->error) and $response->error != null) throw new \ErrorException($response->description, $response->error);

        return $response;
    }

    /**
     * @param string $name
     * @param string $description
     * @return sdk\TerminalInfo
     * @throws \ErrorException
     */
    public function createTerminal($name, $description = null) {
        $request = new CREATE_TERMINAL($name);
        $request->type = "dynamical";
        $request->description = $description;

        $response = $this->restClient->CreateTerminal($request);
        if($response == null) throw new \ErrorException("Terminal not created!");
        if(isset($response->error) and $response->error != null) throw new \ErrorException("Terminal Error", $response->error);

        $this->saveTerminal($response->id);

        return $response;
    }

    /**
     * @param string $orderId
     * @param $amount
     * @param string $refundReason
     * @return sdk\RefundInfo
     * @throws \ErrorException
     */
    public function createRefund($orderId, $amount, $refundReason = "") {
        $payment = $this->getPayment($orderId);

        $refund = new REFUND_INFO($amount, $refundReason);
        $request = new CREATE_REFUND($payment->id, $refund);

        $response = $this->restClient->CreateRefund($request);

        if($response == null) throw new \ErrorException("Refund not created!");
        if(isset($response->error) and $response->error != null) throw new \ErrorException($response->description, $response->error);

        return $response;
    }

    /**
     * @param string $orderId
     * @return sdk\PaymentInfo
     * @throws \ErrorException
     */
    public function getPayment($orderId) {
        $request = new GET_PAYMENT_BY_ORDER($orderId);
        $response = $this->restClient->GetPaymentByOrder($request);

        if($response == null) throw new \ErrorException("Payment not created!");
        if(isset($response->error) and $response->error != null) throw new \ErrorException($response->description, $response->error);

        return $response;
    }


    private function saveTerminal($id) {
        file_put_contents("invoice_tid", $id);
    }

    private function getTerminal()
    {
        $request = new GET_TERMINAL(config("invoice.terminal_id"));
        $response = $this->restClient->GetTerminal($request);
        if ($response->successful()) {
            $data = $response->json();
            return $data['id'];
        }
        return null;
    }
}
