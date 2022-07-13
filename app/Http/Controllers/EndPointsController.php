<?php

namespace App\Http\Controllers;

use App\Models\EndPoint;
use Illuminate\Http\Request;

class EndPointsController extends Controller
{
    public function index()
    {
        $endPoints = EndPoint::all();

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
        //
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
