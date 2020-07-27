<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Models a wishlist item
 */
class WishlistItem extends Model
{
    protected
        $table = 'wishlists_items',
        $fillable = ['wishlist_id', 'name'];

    /**
     * Relation: the parent wishlist
     *
     * @return BelongsTo
     */
    public function wishlist()
    {
        return $this->belongsTo(Wishlist::class, 'wishlist_id');
    }
}
