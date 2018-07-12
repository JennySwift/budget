<?php namespace App\Http\Requests\Accounts;

use App\Http\Requests\Request;
use Auth;

/**
 * Class UpdateAccountRequest
 * @package App\Http\Requests\Accounts
 */
class UpdateAccountRequest extends Request
{

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
        $account = $this->route()->parameter('account');
        $user_id = Auth::id();

        return  [
            'name' => 'required|unique:accounts,name,'.$account->id.',id,user_id,'.$user_id
        ];
    }

}
