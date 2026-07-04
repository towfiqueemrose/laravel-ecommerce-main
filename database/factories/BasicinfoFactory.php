<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BasicinfoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'phone_one' =>'+8809648156710',
            'phone_two' =>'+8809648156710',
            'email' =>'support@ayebazar.com',
            'address' => 'A.H. Tower (5th Floor), H# G-71, Alokar Mor, Ghoramara Rajshahi, 6100',
            'logo' => 'public/webview/assets/images/logo.png',
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
