<?php

namespace App\HealthCheck;

use App\Models\HealthCheck;
use Illuminate\Support\Collection;

class Ping extends BaseHealthCheck
{
    public function parseData(Collection $health_checks)
    {
        foreach ($health_checks as $health_check) {
            $health_check->stat_value = intval(str_replace('ms', '', explode(' - ', $health_check->short_summary)[1]));
        }

        return $health_checks;
    }
}

