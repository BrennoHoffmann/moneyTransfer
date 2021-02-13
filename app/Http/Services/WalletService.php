<?php

namespace App\Http\Services;

use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\Wallet;
use App\Models\User;

class WalletService
{
    public function updateWallet($req)
    {
        $lojista = User::find($req->payer);
        if ($lojista->user_type === 1) {
            $error = ["error" => "Lojistas não podem fazer essa operação"];
            return response()->json($error, 401);
        }
        DB::beginTransaction();
        try {
            $payer = Wallet::where('payee', $req->payer)->first();
            $payee = Wallet::where('payee', $req->payee)->first();

            if ($payer->money < $req->value) {
                $error = ["error" => "Saldo insuficiente"];
                return response()->json($error, 401);
            }
            $payer->money = $payer->money - $req->value;

            $payee->money = $payee->money + $req->value;
            $payee->payee = $req->payee;

            $authMock = 'https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6';
            $decodedMock = json_decode(file_get_contents($authMock));

            if ($decodedMock->message == 'Autorizado' && $payee->payee != $payer->payee) {
                $payeeResult = $payee->save();
                $payerResult = $payer->save();
                DB::commit();
                if ($payeeResult && $payerResult) {
                    $notificationMock = 'https://run.mocky.io/v3/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04';
                    $successo = json_decode(file_get_contents($notificationMock));

                    return response()->json($successo, 200);
                }
            } else {
                $error = ["error" => "Transação não autorizada"];
                return response()->json($error, 401);
            }
        } catch (Exception $e) {
            DB::rollback();
            $error = ["error" => "Não foi possivel completar esta operação"];
            return response()->json($error, 401);
        }
    }
}
