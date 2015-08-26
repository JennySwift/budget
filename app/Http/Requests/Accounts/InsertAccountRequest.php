<?php namespace App\Http\Requests\Accounts;

use App\Http\Requests\Request;
use Auth;

/**
 * Class InsertAccountRequest
 * @package App\Http\Requests\Accounts
 */
class InsertAccountRequest extends Request
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
        $user = Auth::id();

        return  [
            'name' => 'required|unique:accounts,name,NULL,id,user_id,'.$user
        ];
    }

}
