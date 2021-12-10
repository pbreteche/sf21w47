<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\ParameterBag;

class PostCriteriaBuilder
{
    const CRITERIA_NAMES = [
        'title' => 'string',
        'created_after' => 'DateTime',
        'created_before' => 'DateTime',
        'written_by_id' => 'int',
    ];

    public function build(ParameterBag $query)
    {
        $criteria = [];

        foreach ($query as $key => $value) {
            if (!key_exists($key, self::CRITERIA_NAMES)) {
                continue;
            }

            switch (self::CRITERIA_NAMES[$key]) {
                case 'int':
                    if (is_numeric($value)) {
                        $normalizedValue = intval($value);
                        break;
                    }
                    continue 2;
                case 'DateTime':
                    try {
                        $normalizedValue = new \DateTimeImmutable($value);
                        break;
                    } catch(\Throwable $error) {
                        continue 2;
                    }
                default:
                    $normalizedValue = $value;
            }

            $criteria[$key] = $normalizedValue;
        }

        return $criteria;
    }
}