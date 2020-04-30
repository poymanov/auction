<?php

declare(strict_types=1);

namespace App\Http\Validator;

use LogicException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

class ValidationException extends LogicException
{
    /**
     * @var ConstraintViolationListInterface
     */
    private ConstraintViolationListInterface $violations;

    /**
     * @param ConstraintViolationListInterface $violations
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        ConstraintViolationListInterface $violations,
        string $message = 'Invalid input.',
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->violations = $violations;
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }
}