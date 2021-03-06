<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Fluent;

class EndPoint extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'url', 'is_monitored', 'is_staging', 'ping_default_url'];

    public $health_checks_failed = false;

    protected $casts = [
        'last_health_check_timestamp' => 'datetime'
    ];

    public function health_checks()
    {
        return $this->hasMany(HealthCheck::class, 'end_point_id');
    }

    public function last_health_checks($name = null)
    {
        return $this->health_checks
            ->where('finished_at', $this->last_health_check_timestamp);
    }

    public function last_health_check_by_name($name)
    {
        return HealthCheck::where('end_point_id', $this->id)
            ->where('name', $name)
            ->orderBy('finished_at', 'desc')
            ->first();
    }

    static function getFailedEndPoints()
    {
        $endPoints = self::with('health_checks');
    }

    public function has_failed()
    {
        $has_health_check_failed = $this->last_health_checks()->where('name', 'HealthCheckFailed')->count() > 0;
        $has_bad_ping_status = $this->last_health_checks()->where('name', 'Ping')->where('status', 'failed')->count() > 0;

        return $has_health_check_failed || $has_bad_ping_status;
    }

    public function has_no_data()
    {
        $has_no_health_check = $this->last_health_checks()->count() == 0;

        return $has_no_health_check;
    }

    protected static function booted()
    {
        parent::booted(); // TODO: Change the autogenerated stub
    }

    public function getHealthChecks() {
        if (!$this->is_monitored) return;

        $response = Http::withoutVerifying()
            ->withHeaders(['X-Server-Authorization' => env('TRUSTUP_SERVER_AUTHORIZATION')])
            ->get($this->url . '/trustup-io/health/json');

        try {
            return new Fluent(json_decode($response->body()));
        } catch (\Exception $e) {
            $this->health_checks_failed = true;
            return null;
        }
    }
}
