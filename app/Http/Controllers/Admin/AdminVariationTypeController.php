<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VariationType;
use App\Models\VariationTypeName;
use App\Models\VariationValue;

class AdminVariationTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function showVariationType()
    {
        return Inertia::render('Admin/variation_type/VariationTypeManagement');
    }

    public function getVariationTypeDataValue($variation_id)
    {
        $variationTypes = VariationType::where('variation_id', $variation_id)
            ->with('values')
            ->get()
            ->map(function ($variationType) {
                return [
                    'type_id' => $variationType->id,
                    'type_name' => $variationType->name,
                    'values' => $variationType->values->map(function ($value) {
                        return [
                            'value_id' => $value->id,
                            'value_name' => $value->value,
                        ];
                    })
                ];
            });

        return response()->json($variationTypes);
    }



    public function getVariationTypeValueData($variation_id)
    {
        $variationTypes = VariationType::where('variation_id', $variation_id)
            ->with('values')
            ->get();

        if ($variationTypes->isEmpty()) {
            return response()->json(['message' => 'No VariationTypes found for the given Variation ID'], 404);
        }

        $formattedData = $variationTypes->map(function ($variationType) {
            return [
                'id' => $variationType->id,
                'variation_type_name' => $variationType->name,
                'values' => $variationType->values->pluck('value')->unique()->implode(', '),
            ];
        });

        return response()->json($formattedData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    public function getVariationTypeData($id)
    {
        $variationTypes = VariationType::where('variation_id', $id)->get();
        return response()->json($variationTypes);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'variation_type_Id' => 'required|integer|exists:variation_types,id',
            'values' => 'required|array'
        ]);

        foreach ($validatedData['values'] as $variation) {
            VariationValue::create([
                'variation_type_id' => $validatedData['variation_type_Id'],
                'value' => $variation['value'],
            ]);
        }


        return response()->json(['message' => 'Variation types saved successfully.']);
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
    public function destroy($id)
    {
        VariationType::where('id', $id)->delete();
        return response()->json(['message' => "All variation types with types have been deleted successfully."]);
    }
}
