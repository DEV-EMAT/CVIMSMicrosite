<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use ReCaptcha\ReCaptcha;

class ReCaptchaRule implements Rule
{
    public function __construct()
    {}
    
    public function passes($attribute, $value)
    {
        $recapcha = new ReCaptcha(env('GOOGLE_RECAPTCHA_SECRET'));
        $response = $recapcha->verify($value, $_SERVER['REMOTE_ADDR']);
        return $response->isSuccess();
    }
    
    public function message()
    {
       return 'Please comtinue the recaptcha to submit the form!';
    }
}
