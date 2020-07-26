<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
