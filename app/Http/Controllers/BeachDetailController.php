<?php

namespace App\Http\Controllers;

use App\Models\BeachDetail;
use Illuminate\Http\Request;

class BeachDetailController extends Controller
{
    public function index()
    {
        return BeachDetail::all();
    }

    public function show($id)
    {
        return BeachDetail::findOrFail($id);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'beach_id' => 'required|exists:beaches,id',
            'long_description' => 'required',
            'highlight_quote' => 'nullable',
            'long_description2' => 'nullable',
            'tags' => 'nullable',
        ]);
        return BeachDetail::create($data);
    }

    public function update(Request $request, $id)
    {
        $beachDetail = BeachDetail::findOrFail($id);
        $beachDetail->update($request->all());
        return $beachDetail;
    }

    public function destroy($id)
    {
        BeachDetail::destroy($id);
        return response()->noContent();
    }
} 