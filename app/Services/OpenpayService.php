<?php

namespace App\Services;

use Openpay\Data\Openpay;

class OpenpayService
{
    protected $openpay;

    public function __construct()
    {
        $merchantId = config('openpay.merchant_id');
        $privateKey = config('openpay.private_key');
        $publicKey = config('openpay.public_key');
        $country = config('openpay.country');
        $production = config('openpay.production');

        $this->openpay  = Openpay::getInstance($merchantId,
            $privateKey, $country, '127.0.0.1');
    }

    public function createCharge($data)
    {
        $customer = [
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'phone_number' => $data['phone_number'],
            'email' => $data['email']
        ];

        $chargeData = [
            'method' => 'card',
            'source_id' => $data['token_id'],
            'amount' => $data['amount'],
            'currency' => 'PEN',
            'description' => $data['description'],
            'device_session_id' => $data['device_session_id'],
            'customer' => $customer
        ];

        try {
            // Crear el cargo
            $charge = $this->openpay->charges->create($chargeData);
            $chargeId = $charge->id;

            // Recuperar el cargo para ver su estado
            $retrievedCharge = $this->openpay->charges->get($chargeId);

            // Obtener el estado del cargo
            $status = $retrievedCharge->status;

            // Verificar si el estado del cargo es 'completed'
            if ($status === 'completed') {
                return [
                    'message' => "Pago exitoso",
                    'charge_id' => $chargeId,
                    'status' => $status
                ];
            } else {
                return [
                    'message' => "El pago no fue exitoso",
                    'charge_id' => $chargeId,
                    'status' => $status
                ];
            }
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
