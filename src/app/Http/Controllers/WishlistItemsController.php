<?php

namespace App\Http\Controllers;

use App\WishlistItem;
use Illuminate\Http\Request;
use App\Wishlist;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\WishlistItemCreateRequest;
use App\Http\Requests\WishlistItemEditRequest;
use App\Http\Resources\WishlistItemResource;

class WishlistItemsController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WishlistItemCreateRequest $request, Wishlist $wishlist)
    {
        $item = $wishlist->items()->create(
            [
                'name' => $request->input('name')
            ]
        );

        return new WishlistItemResource( $item );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\WishlistItem  $wishlistItem
     * @return \Illuminate\Http\Response
     */
    public function show(WishlistItem $wishlistItem)
    {
        Gate::authorize('view', $wishlistItem);
        return new WishlistItemResource( $wishlistItem );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Wishlist $wishlist
     * @param  \App\WishlistItem  $wishlistItem
     * @return \Illuminate\Http\Response
     */
    public function update(WishlistItemEditRequest $request, Wishlist $wishlist, WishlistItem $wishlistItem)
    {
        $wishlistItem->fill( $request->only(['name']) );
        $wishlistItem->save();
        return new WishlistItemResource( $wishlistItem );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Wishlist $wishlist
     * @param  \App\WishlistItem  $wishlistItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wishlist $wishlist, WishlistItem $wishlistItem)
    {
        Gate::authorize('delete-item', [$wishlist, $wishlistItem]);

        Gate::authorize('delete', $wishlistItem);

        $wishlistItem->delete();
    }
}
