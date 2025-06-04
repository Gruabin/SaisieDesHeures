<?php

namespace App\Form\Activite;

use App\Repository\ActiviteRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ActiviteExistValidator extends ConstraintValidator
{
    public function __construct(private ActiviteRepository $repository) {}

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ActiviteExist) {
            throw new \LogicException('Invalid constraint');
        }

        if (!$value || !ctype_digit((string)$value)) {
            return;
        }

        if (!$this->repository->find($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ id }}', $value)
                ->addViolation();
        }
    }
}
