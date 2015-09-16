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
    public function handle(TransactionWasCreated $event)
    {
        $transaction = $event->transaction;
        // Put an amount into savings if it is an income transaction
        if ($transaction->type === Transaction::TYPE_INCOME) {
            $savings = Savings::forCurrentUser()->first();
            $savings->increase($this->savingsRepository->calculateAfterIncomeAdded($transaction));
        }
    }
}
