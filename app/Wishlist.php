<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Models a wishlist
 */
class Wishlist extends Model
{
    protected
        $table = 'wishlists',
        $fillable = ['user_id', 'name'];

    /**
     * Relation: the wishlist items
     *
     * @return HasMany
     */
    public function items()
    {
        return $this->hasMany(WishlistItem::class, 'wishlist_id');
    }

    /**
     * Return a total by wishlist 
     *
     * @return array
     */
    public static function wishlistReport()
    {
        return DB::table('wishlists')->
        join('users', 'wishlists.user_id', '=', 'users.id')->
        leftJoin('wishlists_items', 'wishlists.id', '=', 'wishlists_items.wishlist_id')->
        select('users.name as user', 'wishlists.name as wishlist', DB::raw('count(`wishlists_items`.`id`) as total'))->
        groupBy('wishlists_items.wishlist_id', 'users.name', 'wishlists.name')->
        orderBy('users.name')->
        get();
    }
}
