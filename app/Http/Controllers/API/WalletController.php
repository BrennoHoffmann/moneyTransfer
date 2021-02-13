<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Validator;
use App\Models\Wallet;
use App\Models\User;
use App\Http\Services\WalletService;
class WalletController extends Controller
{
    protected $WalletService;

    public function __construct(WalletService $WalletService)
    {
        $this->WalletService = $WalletService;
    }

    public function list()
    {
        return Wallet::all();
    }

    public function add(Request $req)
    {
        $validationRules = array(
            'payee' => 'required|min:1',
        );
        $validator = Validator::make($req->all(), $validationRules);
        if ($validator->fails()) {
            return $validator->errors();
        } else {
            try {
                $exception = DB::transaction(function () use ($req) {
                    $wallet = new Wallet;
                    $wallet->money = $req->value;
                    $wallet->payee = $req->payee;

                    $wallet->save();
                });
                $successo = ["successo" => "Deu certo"];
                return response()->json($successo, 200);
            } catch (Exception $e) {
                $successo = ["successo" => "Deu ruim"];
                return response()->json($successo, 401);
            }
        }
    }

    public function update(Request $req)
    {
        $validationRules = array(
            'payee' => 'required|min:1',
            'payer' => 'required|min:1',
        );
        $validator = Validator::make($req->all(), $validationRules);
        if ($validator->fails()) {
            return $validator->errors();
        } else {
            $data = $req->only([
                'value',
                'payee',
                'payer'
            ]);
            $this->WalletService->updateWallet($req);
        }
    }
}
