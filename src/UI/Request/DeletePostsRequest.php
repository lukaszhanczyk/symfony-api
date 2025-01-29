<?php

namespace App\UI\Request;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class DeletePostsRequest
{
    #[Type('string')]
    #[NotBlank]
    public string $id;
}