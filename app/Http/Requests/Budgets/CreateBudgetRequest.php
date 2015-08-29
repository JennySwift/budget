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
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'type' => 'required|in:'.implode(',', Config::get('budgets.types')),
			'name' => 'required|unique:budgets,name,NULL,id,user_id,'.Auth::id(),
			'amount' => 'required|numeric|min:0',
			'starting_date' => 'required|date_format:Y-m-d'
		];
	}

}
