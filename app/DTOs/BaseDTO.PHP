<?php

namespace App\DTOs;

use Carbon\Carbon;
use BackedEnum;

abstract class BaseDTO
{
    public function toArray(): array
    {
        $data = [];

        foreach (get_object_vars($this) as $key => $value) {
            if ($value instanceof BackedEnum) {
                $data[$key] = $value->value;
            }
            elseif ($value instanceof Carbon) {
                $data[$key] = $value->toIso8601String();
            }
            else {
                $data[$key] = $value;
            }
        }
        return $data;
    }
}
