<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\WalletResource;

class WalletController extends Controller
{
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
            $wallet = new Wallet;
            $wallet->money = $req->value;
            $wallet->payee = $req->payee;

            $result = $wallet->save();

            if ($result) {
                return ["Result" => "Deu certo"];
            } else {
                return ["Result" => "Deu ruim"];
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
                return ["Result" => "Lojistas nao podem fazer essa operacao"];
            }

            $payer = Wallet::where('payee', $req->payer)->first();
            $payee = Wallet::where('payee', $req->payee)->first();

            if ($payer->money < $req->value) {
                return ["Result" => "Saldo insuficiente"];
            }
            $payer->money = $payer->money - $req->value;

            $payee->money = $payee->money + $req->value;
            $payee->payee = $req->payee;

            $authMock = 'https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6';
            $decodedMock = json_decode(file_get_contents($authMock));

            if ($decodedMock->message == 'Autorizado') {
                $payeeResult = $payee->save();
                $payerResult = $payer->save();

                if ($payeeResult && $payerResult) {
                    $notificationMock = 'https://run.mocky.io/v3/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04';
                    $decodeMock = file_get_contents($notificationMock);

                    return $decodeMock;
                }
            } else {
                return ["Result" => "Transação não autorizada"];
            }
        }
    }
}
