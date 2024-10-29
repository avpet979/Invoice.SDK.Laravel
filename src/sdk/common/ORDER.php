<?php
namespace invoice\payment\sdk\common;

class ORDER
{
    /**
     * @var string
     */
    public $currency;
    /**
     * @var double
     */
    public $amount;
    /**
     * @var string
     */
    public $description;
    /**
     * @var string
     */
    public $id;

    /**
     * ORDER constructor
     * @param $amount
     */
    public function __construct($amount, $id)
    {
        $this->id = $id;

        $this->currency = 'RUB';

        $this->amount = $amount;

        $this->description = 'Оплата заказа #'. $this->id;
    }
}
