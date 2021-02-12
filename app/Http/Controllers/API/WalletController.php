<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
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
            'payee'=>'required|min:1',
        );
        $validator = Validator::make($req->all(), $validationRules);
        if($validator->fails()){
            return $validator->errors();
        }else{
            $wallet = new Wallet;
            $wallet->money = $req->money;
            $wallet->payee = $req->payee;
    
            $result = $wallet->save();
    
            if($result){
                return ["Result" =>"Deu certo"];
            }else{
                return ["Result" =>"Deu ruim"];
            }
        }
    }

    public function update(Request $req)
    {
        $validationRules = array(
            'payee'=>'required|min:1',
        );
        $validator = Validator::make($req->all(), $validationRules);
        if($validator->fails()){
            return $validator->errors();
        }else{
            $wallet = Wallet::find($req->id);
            $wallet->money = $req->money;
            $wallet->payee = $req->payee;
    
            $result = $wallet->save();
            if($result){
                return ["Result" =>"Put certo"];
            }
            else{
                return ["Result" =>"Put ruim"];
            }
        }

    }
}