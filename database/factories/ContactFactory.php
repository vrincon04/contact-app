<?php

namespace Database\Factories;

use App\Helpers\CreditCardHelper;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $creditCard = CreditCardHelper::make($this->faker->creditCardNumber);

        return [
            'user_id' => 1,
            'name' => $this->faker->name,
            'birthday' => $this->faker->date('Y-m-d'),
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'credit_card' => $creditCard->get(),
            'brand' => $creditCard->getBranch(),
            'email' => $this->faker->email
        ];
    }
}
