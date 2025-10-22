<?php

namespace App\Dto;

use ApiPlatform\Validator\Exception\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseDto
{
    public function validate(ValidatorInterface $validator): self
    {
        $violations = $validator->validate($this);

        if (count($violations) > 0) {
            throw new ValidationException($violations);
        }

        return $this;
    }
}
