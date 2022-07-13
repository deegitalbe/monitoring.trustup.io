<?php

namespace Database\Seeders;

use App\Models\EndPoint;
use Illuminate\Database\Seeder;

class EndPointSeeder extends Seeder
{
    public $endPoints = [
        ['name' => 'www.trustup.be', 'url' => 'https://trustup.be.test', 'is_monitored' => true],
        ['name' => 'my.trustup.pro', 'url' => 'https://my.trustup.pro.test', 'is_monitored' => true],
        ['name' => 'auth.trustup.io', 'url' => 'https://auth.trustup.io.test', 'is_monitored' => true],
    ];

    public function run()
    {
        foreach ($this->endPoints as $endPoint)
            EndPoint::factory()->create($endPoint);
    }
}
