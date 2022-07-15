<?php

namespace App\HealthCheck;

use Illuminate\Support\Collection;

class CpuLoad extends BaseHealthCheck
{
    public function parseData(Collection $health_checks)
    {
        foreach ($health_checks as $health_check) {
            $health_check->stat_value = intval(explode(' ', $health_check->short_summary)[0]);
        }

        return $health_checks;
    }
}
