<?php

namespace App\HealthCheck;

use Illuminate\Support\Collection;

class UsedDiskSpace extends BaseHealthCheck
{
    public function parseData(Collection $health_checks)
    {
        foreach ($health_checks as $health_check) {
            $health_check->stat_value = intval(str_replace('%', '', $health_check->short_summary));
        }

        return $health_checks;
    }
}
