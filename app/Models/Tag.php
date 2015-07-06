<?php namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Tag
 * @package App\Models
 */
class Tag extends Model
{

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function transactions()
    {
        return $this->belongsToMany('App\Models\Transaction', 'transactions_tags');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function budget()
    {
        return $this->belongsTo('App\Models\Budget');
    }

    /**
     *
     * @param $user_id
     * @return mixed
     */
    public static function getTagsWithFixedBudget($user_id)
    {
        $sql = "SELECT id, name, fixed_budget, starting_date FROM tags WHERE flex_budget IS NULL AND fixed_budget IS NOT NULL AND user_id = $user_id ORDER BY name ASC;";
        $tags = DB::select($sql);

        return $tags;
    }

    /**
     *
     * @param $user_id
     * @return mixed
     */
    public static function getTagsWithFlexBudget($user_id)
    {
        $sql = "SELECT id, name, fixed_budget, flex_budget, starting_date FROM tags WHERE flex_budget IS NOT NULL AND user_id = $user_id ORDER BY name ASC;";
        $tags = DB::select($sql);

        return $tags;
    }

    /**
     *
     * @param $tag
     */
    public function setAllocationType($tag)
    {
        $allocated_fixed = $tag->pivot->allocated_fixed;
        $allocated_percent = $tag->pivot->allocated_percent;

        if (isset($allocated_fixed) && !$allocated_percent) {
            $tag->allocation_type = 'fixed';
        } elseif ($allocated_percent && !$allocated_fixed) {
            $tag->allocation_type = 'percent';
        }
    }
}
