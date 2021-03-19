<?php


namespace App\Helpers;


use App\Helpers\Bases\BaseCreditCardHelper;

class CreditCardHelper extends BaseCreditCardHelper
{
    /**
     * @var string[]
     */
    private $typePatters = [
        'Visa' => '/^4[0-9]{0,15}$/i',
        'MasterCard' => '/^5[1-5][0-9]{5,}|222[1-9][0-9]{3,}|22[3-9][0-9]{4,}|2[3-6][0-9]{5,}|27[01][0-9]{4,}|2720[0-9]{3,}$/i',
        'Amex' => '/^3$|^3[47][0-9]{0,13}$/i',
        'Discover' => '/^6$|^6[05]$|^601[1]?$|^65[0-9][0-9]?$|^6(?:011|5[0-9]{2})[0-9]{0,12}$/i',
        'JCB' => '/^(?:2131|1800|35[0-9]{3})[0-9]{3,}$/i',
        'DinersClub' => '/^3(?:0[0-5]|[68][0-9])[0-9]{4,}$/i',
    ];

    /**
     * @param $number
     * @return CreditCardHelper
     */
    public static function make($number): CreditCardHelper
    {
        return new self($number);
    }

    public function getBranch(): string
    {
        $brand = 'Invalid Card';

        foreach ($this->typePatters as $key => $value)
        {
            if(preg_match($value, $this->number)) {
                $brand = $key;
                break;
            }
        }

        return $brand;
    }
}
