<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Variation;
use Illuminate\Http\Request;
use App\Models\VariationName;
use App\Http\Controllers\Controller;
use App\Models\VariationType;

class AdminVariationController extends Controller
{

    public function showVariation()
    {
        return Inertia::render('Admin/variations/VariationManagement');
    }

    public function getVariationData()
    {
        $variations = Variation::all();
        return response()->json($variations);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all variations with their associated types
        $variations = Variation::with('types')->get();

        // Format the result with the types as comma-separated strings
        $formattedVariations = $variations->map(function ($variation) {
            // Get the type names and convert them to a comma-separated string
            $typeNames = $variation->types->pluck('name')->implode(', ');
            return [
                'variation_id' => $variation->id,
                'variation_name' => $variation->name,
                'variation_types' => $typeNames,
            ];
        });

        return response()->json($formattedVariations);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'values' => 'required|array'
        ]);

        $variationTypeName = Variation::where('name', $validatedData['name'])->first();

        if ($variationTypeName) {
            return response()->json(['error' => " Variation already exist. Please update variation."], 422);
        } else {

            $newVariation = Variation::create([
                'name' => $validatedData['name'],
            ]);

            foreach ($validatedData['values'] as $variation) {
                VariationType::create([
                    'variation_id' => $newVariation->id,
                    'name' => $variation['value'],
                ]);
            }

            return response()->json(['message' => 'Variation types saved successfully.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Variation::where('id', $id)->delete();
        return response()->json(['message' => "Variation have been deleted successfully."]);
    }
}
