<?php


namespace App\Helpers\Bases;


abstract class BaseCreditCardHelper
{
    /**
     * @var string
     * Credit Card Number.
     */
    protected $number;

    /**
     * CreditCardHelper constructor.
     * @param $number
     */
    public function __construct($number)
    {
        $this->number = preg_replace('/[\s-]/i', '', $number);
    }

    public function get(): string
    {
        return $this->mask();
    }

    private function mask(): string
    {
        return str_pad(substr($this->number, -4), strlen($this->number), '*', STR_PAD_LEFT);
    }

    abstract public function getBranch(): string;
}
