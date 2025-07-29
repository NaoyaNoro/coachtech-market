<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Transaction;
use App\Models\User;

class TransactionCompleteMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    // public function __construct(
    //     public Transaction $transaction,
    //     public int $value,
    //     public int $sender_id

    // )
    // {
    //     //
    // }

    protected Transaction $transaction;
    protected int $value;
    protected int $sender_id;


    public function __construct(Transaction $transaction, int $value, int $sender_id)
    {
        $this->transaction = $transaction;
        $this->value = $value;
        $this->sender_id = $sender_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $sender= User::find($this->sender_id);
        // $receiver=User::find($this->receiver_id);
        $receiver = $this->transaction->buyer_id === $this->sender_id
            ? User::find($this->transaction->seller_id)
            : User::find($this->transaction->buyer_id);

        return $this->subject("{$sender->name} さんからの評価を受け取りました")
            ->view('emails.value_received')
            ->with([
                'transaction' => $this->transaction,
                'value' => $this->value,
                'sender' => $sender,
                'receiver' => $receiver,
            ]);
    }
}
