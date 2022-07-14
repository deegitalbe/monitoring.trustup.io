<?php

namespace App\HealthCheck;

use Illuminate\Support\Str;

class BaseHealthCheck
{
    public $data;
    public $health_check_name;

    public function __construct()
    {
        $this->health_check_name = lcfirst(substr(strrchr(get_class($this), "\\"), 1));
    }

    public function set_data($data)
    {
        $this->data = $data;
    }

    public function view()
    {
        return view('health_checks.'.$this->health_check_name, [
            'datas' => $this->data
        ]);
    }
}
