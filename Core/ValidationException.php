<?php

namespace Core;

class ValidationException extends \Exception
{
    // keep short property names for compatibility, but add clearer getters
    public readonly array $errors;
    public readonly array $old;

    public function __construct(array $errors = [], array $old = [])
    {
        parent::__construct('The form failed to validate.');

        $this->errors = $errors;
        $this->old = $old;
    }

    public static function throw($errors, $old)
    {
       throw new static($errors, $old);
    }

    // clearer method names for Spanish-speaking devs to understand
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getOldInput(): array
    {
        return $this->old;
    }
}