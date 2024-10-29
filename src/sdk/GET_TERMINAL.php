<?php
namespace invoice\payment\sdk;

class GET_TERMINAL
{
    /**
     * @var string
     */
    public $alias;
    /**
     * @var string
     */
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
}