<?php namespace App\Http\Requests\Tags;

use Auth;
use App\Http\Requests\Request;

/**
 * Class InsertTagRequest
 * @package App\Http\Requests\Tags
 */
class InsertTagRequest extends Request
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
        $user_id = Auth::id();

        return [
            'name' => 'required|unique:tags,name,NULL,id,user_id,'.$user_id
        ];
    }

}
