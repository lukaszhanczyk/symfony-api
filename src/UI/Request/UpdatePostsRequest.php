<?php

namespace App\UI\Request;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class UpdatePostsRequest
{
    #[Type('string')]
    #[Length(max: 180)]
    #[NotBlank]
    public string $id;

    #[Type('string')]
    #[Length(max: 180)]
    #[NotBlank]
    public string $title;

    #[Type('string')]
    #[Length(max: 500)]
    #[NotBlank]
    public string $postContent;
}