<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

/**
 * Class Color
 * @package App\Models
 */
class Color extends Model
{

    /**
     * @var array
     */
    protected $fillable = ['item', 'color'];

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     *
     * @return array
     */
    public static function getColors()
    {
        $user_id = Auth::user()->id;

        $income = Color::where('item', 'income')
            ->where('user_id', $user_id)
            ->pluck('color');

        $expense = Color::where('item', 'expense')
            ->where('user_id', $user_id)
            ->pluck('color');

        $transfer = Color::where('item', 'transfer')
            ->where('user_id', $user_id)
            ->pluck('color');

        $colors = array(
            "income" => $income,
            "expense" => $expense,
            "transfer" => $transfer
        );

        return $colors;
    }

}
