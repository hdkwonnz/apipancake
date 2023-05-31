<?php

namespace App\Http\Controllers\Api;

use App\Models\Secret;
use App\Models\Promotion;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PromotionController extends Controller
{
    // public function checkPmCode($apiKey, $pmCode)
    public function checkPmCode($pmCode, Request $request)
    {
        $apiKey = $request->header('Authorization');

        $secret = Secret::where('api_key', '=', $apiKey)
            ->first();
        if (!$secret) {
            return response()->json([
                'promotion' => "nok",
                'dcRate' => 0,
            ]);
        }

        $date = Carbon::now()->format('Y-m-d');

        // $promotion = Promotion::where('code', '=', $pmCode)
        $promotion = Promotion::where(DB::raw('BINARY `code`'), '=', $pmCode)
            ->first();
        if (!$promotion) {
            return response()->json([
                'promotion' => "nok",
                'dcRate' => 0,
            ]);
        }
        if ($promotion->expiery < $date) {
            return response()->json([
                'promotion' => "nok",
                'dcRate' => 0,
            ]);
        }
        if ($promotion->cur_use < $promotion->max_use) {
            return response()->json([
                'promotion' => "ok",
                'dcRate' => $promotion->dc_rate,
            ]);
        } else {
            return response()->json([
                'promotion' => "nok",
                'dcRate' => 0,
            ]);
        }
    }

    // public function getPromotion($apiKey, $pmCode)
    public function getPromotion($pmCode, Request $request)
    {
        $apiKey = $request->header('Authorization');

        $secret = Secret::where('api_key', '=', $apiKey)
            ->first();
        if (!$secret) {
            return response()->json([
                'branch' => null,
                'expiery' => null,
            ]);
        }

        $date = Carbon::now()->format('Y-m-d');

        $promotion = Promotion::where('code', '=', $pmCode)
            ->first();
        if (!$promotion) {
            return response()->json([
                'branch' => null,
                'expiery' => null,
            ]);
        }
        if ($promotion->expiery < $date) {
            return response()->json([
                'branch' => null,
                'expiery' => null,
            ]);
        }

        return response()->json([
            'branch' => $promotion->branch,
            'expiery' => $promotion->expiery,
        ]);
    }

    public function updatePmCode(Request $request)
    {
        $secret = Secret::where('api_key', '=', $request->api_key)
            ->first();
        if (!$secret) {
            return response()->json([
                'rerult' => 'nok',
            ]);
        }

        $promotion = Promotion::where('code', '=', $request->pmCode)
            ->first();
        if ($promotion->cur_use < $promotion->max_use) {
            $promotion->cur_use = $promotion->cur_use + 1;
            $promotion->update();
            return response()->json([
                'rerult' => 'ok',
            ]);
        } else {
            return response()->json([
                'rerult' => 'nok',
            ]);
        }
    }

    public function createPmCode(Request $request)
    {
        $secret = Secret::where('api_key', '=', $request->api_key)
            ->where('branch_code', '=', $request->branch_code)
            ->first();

        if (!$secret) {
            return response()->json([
                'pmCode' => null,
            ]);
        }

        $branchCode = $request->branch_code;
        $maxIssue = $request->max_issue;
        $expieryDate = $request->expiery;

        $count = Promotion::where('branch', '=', $branchCode)
            ->where('expiery', '=', $expieryDate)
            ->count();
        if ($count >= $maxIssue) {
            return response()->json([
                'pmCode' => null,
            ]);
        }

        $pmCode = $this->unique_str();

        Promotion::create([
            'code' => $pmCode,
            'branch' => $request->branch_code,
            'expiery' => $request->expiery,
            'max_use' => $request->max_use,
            'dc_rate' => $request->dc_rate,
        ]);

        return response()->json([
            'pmCode' => $pmCode,
        ]);
    }

    private function unique_str()
    {
        $uniqueStr = Str::random(8);

        // while (Promotion::where('code', $uniqueStr)->exists()) {
        //     $uniqueStr = Str::random(8);
        // }
        while (Promotion::where(DB::raw('BINARY `code`'), $uniqueStr)->exists()) {
            $uniqueStr = Str::random(8);
        }

        return $uniqueStr;
    }

    // public function showCouponList($apiKey, $branchCode)
    public function showCouponList($branchCode, Request $request)
    {
        $apiKey = $request->header('Authorization');

        $secret = Secret::where('api_key', '=', $apiKey)
            ->first();

        if (!$secret) {
            return;
        }
        if ($secret->role != "admin") {
            return;
        }

        $today = date('Y-m-d');

        $promotions = Promotion::whereDate('expiery', '>=', $today)
            ->where('branch', '=', $branchCode)
            ->orderBy('expiery', 'asc')
            ->orderBy('code', 'asc')
            ->get();

        return response()->json([
            'coupons' => $promotions,
        ]);
    }

    public function deleteExpireCode(Request $request)
    {
        $secret = Secret::where('api_key', '=', $request->api_key)
            ->first();

        if (!$secret) {
            $response = "incorrect api key.";
            return response()->json([
                'response' => $response,
            ]);
        }

        if ($secret->role != 'admin') {
            $response = "no prmission.";
            return response()->json([
                'response' => $response,
            ]);
        }

        $today = date('Y-m-d');
        $promotion = Promotion::whereDate('expiery', '<', $today);
        $promotion->delete();

        $response = "ok";
        return response()->json([
            'response' => $response,
        ]);
    }
}
