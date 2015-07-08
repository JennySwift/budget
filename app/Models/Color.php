<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Color
 * @package App\Models
 */
class Color extends Model
{

    /**
     * @var array
     */
    protected $fillable = ['item', 'color', 'user_id'];

}
