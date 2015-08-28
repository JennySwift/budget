<?php namespace App\Http\Requests\Tags;

use Auth;
use App\Http\Requests\Request;

/**
 * Class UpdateTagNameRequest
 * @package App\Http\Requests\Tags
 */
class UpdateTagNameRequest extends Request
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
        $tag = $this->route()->parameter('tags');
        $user_id = Auth::id();

        return [
            'name' => 'required|unique:tags,name,'.$tag->id.',id,user_id,'.$user_id
        ];
    }

}
