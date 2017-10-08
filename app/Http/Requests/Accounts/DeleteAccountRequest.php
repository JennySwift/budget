<?php namespace App\Http\Requests\Accounts;

use Auth;
use App\Http\Requests\Request;

class DeleteAccountRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		$account = $this->route()->parameter('account');

		return Auth::id() == $account->user->id;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [];
	}

}
