<?php namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting
 * @package App\Models
 */
class Setting extends Model {

    /**
     * @var User
     */
    protected $user;
    /**
     * @var array
     */
    protected $allowed = ['income', 'expense', 'transfer', 'clear_fields'];

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     *
     * @param array $attributes
     * @return mixed
     */
    public function merge(array $attributes)
    {
        $settings = array_merge($this->user->settings, array_only($attributes, $this->allowed));
        return $this->user->update(compact('settings'));
    }

}
