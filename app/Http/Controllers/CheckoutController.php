<?php

namespace App\Http\Controllers;

use App\Services\OpenpayService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected $openpayService;

    public function __construct(OpenpayService $openpayService)
    {
        $this->openpayService = $openpayService;
    }

    public function checkout(Request $request)
    {
        $request['name'] = "ytalo";
        $request['last_name'] = "tuesta";
        $request['phone_number'] = "991284653";
        $request['email'] = "tuestaytalo@gmail.com";
        $request['amount'] = 100;
        $request['description'] = "descripcion base prueba";
        $request['url'] = '127.0.0.1';
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email',
            'token_id' => 'required|string',
            'amount' => 'required|numeric',
            'description' => 'required|string|max:255',
            'device_session_id' => 'required|string',
            'url' => 'required|string'
        ]);

//        return  $validatedData;
        try {
            $charge = $this->openpayService->createCharge($validatedData);
            return response()->json(['success' => true, 'charge' => $charge], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function showCheckoutForm()
    {
        $openpayConfig = [
            'merchant_id' => config('openpay.merchant_id'),
            'public_key' => config('openpay.public_key'),
            'production' => config('openpay.production'),
        ];

        return view('checkout', compact('openpayConfig'));
    }
}
