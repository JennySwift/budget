<?php

namespace App\Events;

use App\Events\Event;
use App\Models\Transaction;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Class TransactionWasUpdated
 * @package App\Events
 */
class TransactionWasUpdated extends Event
{
    use SerializesModels;

    /**
     * @var oldTransaction
     */
    public $oldTransaction;
    /**
     * @var
     */
    public $newTransaction;

    /**
     * Create a new event instance.
     * @param Transaction $oldTransaction
     * @param $newTransaction
     */
    public function __construct(Transaction $oldTransaction, $newTransaction)
    {
        $this->oldTransaction = $oldTransaction;
        $this->newTransaction = $newTransaction;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
