<?php namespace App\Http\Transformers;

use App\Models\Budget;
use App\Models\Transaction;
use League\Fractal\TransformerAbstract;

/**
 * Class BudgetTransformer
 */
class BudgetTransformer extends TransformerAbstract
{
    /**
     * BudgetTransformer constructor.
     * @param array $params
     */
    public function __construct($params = [])
    {
        $this->params = $params;
    }

    /**
     * @param Budget $budget
     * @return array
     */
    public function transform(Budget $budget)
    {
        $data = [
            'id' => $budget->id,
            'path' => $budget->path,
            'name' => $budget->name,
            'type' => $budget->type,
            'transactionsCount' => $budget->transactionsCount
        ];

        if (array_key_exists('includeExtra', $this->params) && $this->params['includeExtra']) {
            $data['amount'] = $budget->amount;
            $data['calculatedAmount'] = $budget->calculatedAmount;
            $data['formattedStartingDate'] = $budget->formattedStartingDate;
            $data['spent'] = $budget->spent;
            $data['received'] = $budget->received;
            $data['spentOnOrAfterStartingDate'] = $budget->spentOnOrAfterStartingDate;
            $data['spentBeforeStartingDate'] = $budget->spentBeforeStartingDate;
            $data['receivedAfterStartingDate'] = $budget->receivedAfterStartingDate;
            $data['cumulativeMonthNumber'] = $budget->cumulativeMonthNumber;
            $data['cumulative'] = $budget->cumulative;
            $data['remaining'] = $budget->remaining;
        }

        return $data;
    }

}