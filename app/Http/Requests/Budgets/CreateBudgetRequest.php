<?php namespace App\Http\Requests\Budgets;

use App\Http\Requests\Request;
use Auth, Config;

class CreateBudgetRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.

     * How would I write my own error message for the required thing here while
     * keeping the same message for when the required thing is used elsewhere?
     * Instead of "The :attribute field is required,"
     * I want: "The :attribute field is required when type is fixed or flex."
     * This question was answered.
	 * @return array
	 */
	public function rules()
	{
        $rules = [
            'name' => 'required|unique:budgets,name,NULL,id,user_id,'.Auth::id(),
            'type' => 'required|in:'.implode(',', Config::get('budgets.types')),

//            'amount' => 'required_if:type,fixed|required_if:type,flex|numeric|min:0',
//			'starting_date' => 'required_if:type,fixed|required_if:type,flex|date_format:Y-m-d'
        ];

        if ($this->get('type') !== 'unassigned') {
            $rules['amount'] = 'required_if:type,fixed|required_if:type,flex|numeric|min:0';
			$rules['starting_date'] = 'required_if:type,fixed|required_if:type,flex|date_format:Y-m-d';
        }

        return $rules;
	}

}
