<?php

namespace App\Policies;

use App\User;
use App\Wishlist;
use App\WishlistItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class WishlistPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Wishlist  $wishlist
     * @return mixed
     */
    public function view(User $user, Wishlist $wishlist)
    {
        return ($user->getKey() == $wishlist->user_id);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Wishlist  $wishlist
     * @return mixed
     */
    public function update(User $user, Wishlist $wishlist)
    {
        // Only the creator can update the model
        return ($user->getKey() == $wishlist->user_id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Wishlist  $wishlist
     * @return mixed
     */
    public function delete(User $user, Wishlist $wishlist)
    {
        // Only the owner can delete the model
        return ($user->getKey() == $wishlist->user_id);
    }


    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Wishlist  $wishlist
     * @return mixed
     */
    public function forceDelete(User $user, Wishlist $wishlist)
    {
        // Only the owner can delete the model
        return ($user->getKey() == $wishlist->user_id);
    }

    /**
     * Determine wheter the user can add an item to the wishlist
     *
     * @param User $user
     * @param Wishlist $wishlist
     * @return mixed
     */
    public function addItem(User $user, Wishlist $wishlist)
    {
        return ($user->getKey() == $wishlist->user_id);
    }

    /**
     * Determine if the wishlist item is editable
     *
     * @param User $user
     * @param Wishlist $wishlist
     * @param WishlistItem $item
     * @return mixed
     */
    public function editItem(User $user, Wishlist $wishlist, WishlistItem $item)
    {
        if ($user->getKey() == $wishlist->user_id) {
            return ($item->wishlist_id == $wishlist->getKey());
        }

        return FALSE;
    }

    /**
     * Determine if the wishlist item is deletable
     *
     * @param User $user
     * @param Wishlist $wishlist
     * @param WishlistItem $item
     * @return mixed
     */
    public function deleteItem(User $user, Wishlist $wishlist, WishlistItem $item)
    {
        if ($user->getKey() == $wishlist->user_id) {
            return ($item->wishlist_id == $wishlist->getKey());
        }

        return FALSE;
    }
}
