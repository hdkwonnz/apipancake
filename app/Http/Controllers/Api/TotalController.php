<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Secret;
use App\Models\Total;
use Illuminate\Http\Request;

class TotalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($apiKey, $branchCode, $from, $to)
    {
        $secret = Secret::where('api_key', '=', $apiKey)
            ->first();

        if (!$secret) {
            return;
        }
        if ($secret->role != "admin") {
            return;
        }

        $totals = Total::whereDate('date', '>=', $from)
            ->whereDate('date', '<=', $to)
            ->where('branch_code', '=', $branchCode)
            ->orderBy('date', 'asc')
            ->get();

        return response()->json([
            'totals' => $totals,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $secret = Secret::where('api_key', '=', $request->api_key)
            ->where('branch_code', '=', $request->branch_code)
            ->first();

        if (!$secret) {
            $response = "api key or branch code incorrect.";
            return response()->json([
                'response' => $response,
            ]);
        }

        $completed = false;

        $total = Total::whereDate('date', '=', $request->date)
            ->where('branch_code', '=', $request->branch_code)
            ->first();

        if ($total) {
            $total->sold_total = $total->sold_total + $request->sold_total;
            $total->canceled_total = $total->canceled_total + $request->canceled_total;
            $total->update();
            $completed = true;
        } else {
            $totalOrder = Total::create([
                'date' => $request->date,
                'branch_code' => $request->branch_code,
                'sold_total' => $request->sold_total,
                'canceled_total' => $request->canceled_total,
            ]);
            $completed = true;
        }

        if ($completed) {
            $response = "updated";
        } else {
            $response = "no updated";
        }

        return response()->json([
            'response' => $response,
        ]);
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
        //
    }
}
