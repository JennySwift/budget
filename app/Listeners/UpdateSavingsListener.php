<?php

namespace App\Listeners;

use App\Models\Savings;
use App\Models\Transaction;
use App\Repositories\Savings\SavingsRepository;
use App\Events\TransactionWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateSavingsListener
{
    /**
     * @var SavingsRepository
     */
    private $savingsRepository;

    /**
     * Create the event listener.
     * @param SavingsRepository $savingsRepository
     */
    public function __construct(SavingsRepository $savingsRepository)
    {
        $this->savingsRepository = $savingsRepository;
    }

    /**
     * Handle the event.
     *
     * @param  TransactionWasCreated  $event
     * @return void
     */
    public function handle($event)
    {
        switch(class_basename($event)) {
            case "TransactionWasCreated":
                $transaction = $event->transaction;

                // Put an amount into savings if it is an income transaction
                if ($transaction->type === Transaction::TYPE_INCOME) {
                    $savings = Savings::forCurrentUser()->first();
                    $savings->increase($this->savingsRepository->calculateAfterIncomeAdded($transaction));
                }
                break;
            case "TransactionWasUpdated":
                $oldTotal = $event->oldTransaction->total;

                if (!isset($event->newTransaction['total'])) {
                    return;
                }

                $newTotal = $event->newTransaction['total'];

                $savings = Savings::forCurrentUser()->first();

                //Todo: Allow for the user to change the type of the transaction
                
                // If it is an income transaction, and if the total has decreased,
                // remove a percentage from savings
                if ($event->oldTransaction->type === 'income' && $newTotal < $oldTotal) {
                    $savings->decrease($this->savingsRepository->calculateAfterDecrease($oldTotal, $newTotal));
                }

                // If it is an income transaction, and if the total has increased,
                // add a percentage to savings
                if ($event->oldTransaction->type === 'income' && $newTotal > $oldTotal) {
                    $savings->increase($this->savingsRepository->calculateAfterIncrease($oldTotal, $newTotal));
                }
                break;
            default:
        }
    }
}
