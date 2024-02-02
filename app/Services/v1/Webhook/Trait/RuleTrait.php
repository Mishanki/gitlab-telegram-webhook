<?php

namespace App\Services\v1\Webhook\Trait;

use App\Services\v1\Webhook\Entity\SendEntity;

trait RuleTrait
{
    /**
     * @param array $rules
     * @param SendEntity $entity
     *
     * @return null|array
     */
    public function ruleWork(array $rules, SendEntity $entity): ?array
    {
        foreach ($rules as $rule) {
            if ($response = $rule::rule($entity)) {
                return $response;
            }
        }

        return null;
    }
}
