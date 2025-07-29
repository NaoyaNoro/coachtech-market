<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Value;
use App\Mail\TransactionCompleteMail;
use Illuminate\Support\Facades\Mail;

class ValueController extends Controller
{
    public function send_value(Request $request)
    {
        $value=$request->value;
        $transaction_id=$request->transaction_id;
        Transaction::find($transaction_id)
        ->update([
            'status' => "completed",
        ]);

        $sender_id = auth()->id();
        $transaction=Transaction::find($transaction_id);
        $isBuyer=$sender_id== $transaction->buyer_id;
        $isSeller=$sender_id== $transaction->seller_id;

        $receiver_id = null;

        if ($isBuyer) {
            $receiver_id = User::find($transaction->seller_id)->id;
        } else if ($isSeller) {
            $receiver_id  = User::find($transaction->buyer_id)->id;
        }

        Value::create([
            'transaction_id' => "$transaction_id",
            'sender_id'=>$sender_id,
            'receiver_id'=>$receiver_id,
            'value'=>$value,
        ]);

        Mail::to(User::find($receiver_id)->email)->send(
            new TransactionCompleteMail($transaction, $value, $sender_id)
        );

        return redirect('/');
    }
}
