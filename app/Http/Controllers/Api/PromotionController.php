<?php

namespace App\Http\Controllers\Api;

use App\Models\Secret;
use App\Models\Promotion;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class PromotionController extends Controller
{
    public function checkPmCode($apiKey, $pmCode)
    {
        $secret = Secret::where('api_key', '=', $apiKey)
            ->first();
        if (!$secret) {
            return response()->json([
                'promotion' => "nok",
            ]);
        }

        $date = Carbon::now()->format('Y-m-d');

        $promotion = Promotion::where('code', '=', $pmCode)
            ->first();
        if (!$promotion) {
            return response()->json([
                'promotion' => "nok",
            ]);
        }
        if ($promotion->expiery < $date) {
            return response()->json([
                'promotion' => "nok",
            ]);
        }
        if ($promotion->cur_use < $promotion->max_use) {
            return response()->json([
                'promotion' => "ok",
            ]);
        } else {
            return response()->json([
                'promotion' => "nok",
            ]);
        }
    }

    public function updatePmCode(Request $request)
    {
        $secret = Secret::where('api_key', '=', $request->api_key)
            ->first();
        if (!$secret) {
            return;
        }

        $promotion = Promotion::where('code', '=', $request->pmCode)
            ->first();
        $promotion->cur_use = $promotion->cur_use + 1;
        $promotion->update();
    }

    public function createPmCode(Request $request)
    {
        $randomString = Str::random(06);
        dd($randomString);
    }
}
