<?php

namespace App\Controller;

use Symfony\Component\Validator\Validator\ValidatorInterface;

trait ErrorsValidateEntity
{
    /**
     * @param mixed $entity
     *
     * @return mixed
     */
    private function validate(ValidatorInterface $validator, $entity)
    {
        $messages = [];
        $errors = $validator->validate($entity);
        if (count($errors) > 0) {
            foreach ($errors as $violation) {
                $messages[] = $violation->getMessage();
            }

            return $messages;
        }

        return false;
    }
}
