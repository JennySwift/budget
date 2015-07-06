<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
//use DB;
use Illuminate\Support\Facades\DB;

class Tag extends Model {

	public function transactions () {
		return $this->belongsToMany('App\Models\Transaction', 'transactions_tags');
	}

	public function budget () {
		return $this->belongsTo('App\Models\Budget');
	}
	
	public static function getTagsWithFixedBudget($user_id)
	{
		$sql = "SELECT id, name, fixed_budget, starting_date FROM tags WHERE flex_budget IS NULL AND fixed_budget IS NOT NULL AND user_id = $user_id ORDER BY name ASC;";
		$tags = DB::select($sql);

		return $tags;
	}

	public static function getTagsWithFlexBudget($user_id)
	{
//        DB::table('tags')->where('user_id', Auth::user()->id)

		$sql = "SELECT id, name, fixed_budget, flex_budget, starting_date FROM tags WHERE flex_budget IS NOT NULL AND user_id = $user_id ORDER BY name ASC;";
		$tags = DB::select($sql);
		
		return $tags;
	}

    public function setAllocationType($tag) {
        $allocated_fixed = $tag->pivot->allocated_fixed;
        $allocated_percent = $tag->pivot->allocated_percent;

        if (isset($allocated_fixed) && !$allocated_percent) {
            $tag->allocation_type = 'fixed';
        }
        elseif ($allocated_percent && !$allocated_fixed) {
            $tag->allocation_type = 'percent';
        }
    }
}
