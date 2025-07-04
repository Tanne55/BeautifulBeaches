<?php

namespace App\Http\Controllers;

use App\Models\TourDetail;
use Illuminate\Http\Request;

class TourDetailController extends Controller
{
    public function index()
    {
        return TourDetail::all();
    }

    public function show($id)
    {
        return TourDetail::findOrFail($id);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'departure_time' => 'nullable',
            'return_time' => 'nullable',
            'included_services' => 'nullable',
            'excluded_services' => 'nullable',
            'highlights' => 'nullable',
        ]);
        return TourDetail::create($data);
    }

    public function update(Request $request, $id)
    {
        $tourDetail = TourDetail::findOrFail($id);
        $tourDetail->update($request->all());
        return $tourDetail;
    }

    public function destroy($id)
    {
        $tourDetail = TourDetail::findOrFail($id);
        $tourDetail->delete();
        return response()->noContent();
    }
} 