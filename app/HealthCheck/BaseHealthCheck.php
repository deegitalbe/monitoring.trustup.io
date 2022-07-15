<?php

namespace App\HealthCheck;

use App\Models\HealthCheck;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class BaseHealthCheck
{
    public $endPoint;
    public $data;
    public $health_check_name;

    public function __construct()
    {
        $this->health_check_name = lcfirst(substr(strrchr(get_class($this), "\\"), 1));
    }

    public function setEndPoint($endPoint)
    {
        $this->endPoint = $endPoint;
    }

    public function parseData(Collection $health_checks)
    {

    }

    // Last hour -> Every five minutes
    // Last 12 hours -> every 30 minutes
    // Last day -> every hour

    public function getData()
    {
        // Last hour -> Every five minutes
        $begin_range = Carbon::now()->subHours(1);


        $health_checks = HealthCheck::where('end_point_id', $this->endPoint->id)
            ->where('name', $this->health_check_name)
            ->where('finished_at', '>=', $begin_range)
            ->get();

        $parsed_health_checks = $this->parseData($health_checks);

        $min_steps = 5;
        $grouped_health_checks = new Collection();

        $now = Carbon::now();
        while ($begin_range < $now) {
            ray($begin_range, $begin_range->copy()->addMinutes($min_steps));
            $grouped_health_checks->push([
                'label' => $begin_range->format('d/m/Y H:i'),
                'value' => $parsed_health_checks->where('finished_at', '>=', $begin_range)
                    ->where('finished_at', '<', $begin_range->copy()->addMinutes($min_steps))
                    ->avg('stat_value')
            ]);

            $begin_range->addMinutes($min_steps);
        }

        return $grouped_health_checks;
    }

    public function view()
    {
        return view('health_checks.'.$this->health_check_name, [
            'datas' => $this->getData() ?? []
        ]);
    }
}
