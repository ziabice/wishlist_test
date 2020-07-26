<?php

namespace App\Policies;

use App\User;
use App\WishlistItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class WishlistItemPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\WishlistItem  $wishlistItem
     * @return mixed
     */
    public function view(User $user, WishlistItem $wishlistItem)
    {
        return ($user->getKey() == $wishlistItem->wishlist->user_id);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\WishlistItem  $wishlistItem
     * @return mixed
     */
    public function update(User $user, WishlistItem $wishlistItem)
    {
        return ($user->getKey() == $wishlistItem->wishlist->user_id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\WishlistItem  $wishlistItem
     * @return mixed
     */
    public function delete(User $user, WishlistItem $wishlistItem)
    {
        return ($user->getKey() == $wishlistItem->wishlist->user_id);
    }

}
