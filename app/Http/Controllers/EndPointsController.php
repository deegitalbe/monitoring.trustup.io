<?php

namespace App\Http\Controllers;

use App\Models\EndPoint;
use App\Models\HealthCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EndPointsController extends Controller
{
    public function index()
    {
        $endPoints = EndPoint::when(request()->search_name, fn($query, $search_name) => $query->where('name', 'like', "%$search_name%"))
            ->when(request()->boolean('show_staging'), fn($query, $show_staging) => null, fn($query, $show_staging) => $query->where('is_staging', false))
            ->orderBy('is_staging', 'desc')
            ->orderBy('name')
            ->get();

        // Load less data as possible
        foreach ($endPoints as $endPoint) {
            $endPoint->load(['health_checks' => function($query) use ($endPoint) {
                $query->where('finished_at', $endPoint->last_health_check_timestamp);
            }]);
        }

        return view('end_points.index', [
             'endPoints' => $endPoints ?? []
        ]);
    }

    public function create()
    {
        return view('end_points.create');
    }

    public function store(Request $request)
    {
        $request->is_monitored = $request->boolean('is_monitored');
        $request->is_staging = $request->boolean('is_staging');

        $request->validate([
            'name' => 'required',
            'url' => 'url',
        ]);

        EndPoint::create([
            'name' => $request->name,
            'url' => $request->url,
            'is_monitored' => $request->boolean('is_monitored'),
            'is_staging' => $request->boolean('is_staging'),
        ]);
        return redirect(route('end-points.index'));
    }

    public function show(EndPoint $endPoint)
    {
        $endPoint->load(['health_checks']);

        $health_check_categories = DB::table('health_checks')
            ->where('end_point_id', $endPoint->id)
            ->select(['name', 'label'])
            ->distinct()
            ->get();

        // Load the class dynamically to get the blade view to render
        if (request()->health_check) {
            $classname = '\\App\\HealthCheck\\'.request()->health_check;
            $health_check_class = new $classname;
            $health_check_class->set_data($endPoint->health_checks->where('name', request()->health_check));
        }

        return view('end_points.show', [
            'endPoint' => $endPoint,
            'health_check_categories' => $health_check_categories,
            'health_check' => $health_check_class ?? null
        ]);
    }

    public function edit(EndPoint $endPoint)
    {
        //
    }

    public function update(Request $request, EndPoint $endPoint)
    {
        //
    }

    public function destroy(EndPoint $endPoint)
    {
        $endPoint->delete();
        return redirect(route('end-points.index'));
    }
}
