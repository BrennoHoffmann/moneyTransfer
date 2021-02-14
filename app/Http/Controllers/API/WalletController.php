<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Validator;
use App\Models\Wallet;
use App\Models\User;
use App\Http\Resources\WalletResource;

class WalletController extends Controller
{
    public function list()
    {
        return Wallet::all();
    }

    public function create(Request $req)
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

    public function delete($id){
        $wallet = Wallet::find($id);
        $result = $wallet->delete();
        if($result){
            $sucesso = ["sucesso" => "Carteira apagada"];
            return response()->json($sucesso, 401);
        }else{
            $error = ["error" => "Transação não autorizada"];
            return response()->json($error, 401);
        }
    }
}
