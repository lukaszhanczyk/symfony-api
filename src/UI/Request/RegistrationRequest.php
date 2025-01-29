<?php

namespace App\UI\Request;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class RegistrationRequest
{
    #[Email]
    #[NotBlank]
    public string $email;

    #[Type('string')]
    #[Length(max: 100)]
    #[NotBlank]
    public string $password;
}