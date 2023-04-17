<?php

namespace App\Http\Controllers\Api;

use App\Models\Secret;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SecretController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($apiKey, $branchCode)
    {
        $secret = Secret::where('api_key', '=', $apiKey)
            ->first();

        if (!$secret) {
            return;
        }
        if ($secret->role != "admin") {
            return;
        }

        $secrets = Secret::all();

        return response()->json([
            'secrets' => $secrets,
        ]);
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

        if ($secret->role != "admin") {
            $response = "You are not admin role.";
            return response()->json([
                'response' => $response,
            ]);
        }

        $secret = Secret::create([
            'branch_code' => $request->new_branch_code,
            'branch_name' => $request->new_branch_name,
            'api_key' => $request->new_api_key,
        ]);

        $response = "ok";
        return response()->json([
            'response' => $response,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($apiKey, $id)
    {
        $secret = Secret::where('api_key', '=', $apiKey)
            ->first();

        if (!$secret) {
            return;
        }
        if ($secret->role != "admin") {
            return;
        }

        $secret = Secret::where('id', '=', $id)
            ->first();

        return response()->json([
            'secret' => $secret,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
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

        if ($secret->role != "admin") {
            $response = "You are not admin role.";
            return response()->json([
                'response' => $response,
            ]);
        }

        $secret = Secret::find($request->secret_id);
        if ($secret->branch_code != 'alba') {
            $secret->branch_code = $request->new_branch_code;
        }
        $secret->branch_name = $request->new_branch_name;
        $secret->api_key = $request->new_api_key;
        $secret->update();

        $response = "ok";
        return response()->json([
            'response' => $response,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
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

        $secret = Secret::find($request->secret_id);
        $secret->delete();

        $response = "ok";
        return response()->json([
            'response' => $response,
        ]);
    }
}
