<?php namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Preference
 * @package App\Models
 */
class Preference extends Model
{
    /**
     * @var User
     */
    protected $user;
    /**
     * @var array
     */
    protected $allowed = [
        'colors',
        'clearFields',
        'dateFormat',
        'autocompleteDescription',
        'autocompleteMerchant',
        'show'
    ];

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
        $preferences = array_merge($this->user->preferences, array_only($attributes, $this->allowed));
        return $this->user->update(compact('preferences'));
    }

}
