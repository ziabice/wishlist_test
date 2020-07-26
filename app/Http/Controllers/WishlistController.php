<?php

namespace App\Http\Controllers;

use App\Http\Requests\WishlistCreateRequest;
use App\Http\Requests\WishlistEditRequest;
use App\Http\Resources\WishlistResource;
use App\User;
use App\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

/**
 * Handles wishlist and wishlist items creation
 */
class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wishlists = Wishlist::where('user_id', '=', Auth::id())->get();
        return WishlistResource::collection( $wishlists );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WishlistCreateRequest $request)
    {
        $data = $request->only(['name']);
        $data['user_id'] = Auth::id();

        $wishlist = Wishlist::create( $data );

        return new WishlistResource( $wishlist );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Wishlist  $wishlist
     * @return \Illuminate\Http\Response
     */
    public function show(Wishlist $wishlist)
    {
        Gate::authorize('view', $wishlist);

        return new WishlistResource( $wishlist );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Wishlist  $wishlist
     * @return \Illuminate\Http\Response
     */
    public function update(WishlistEditRequest $request, Wishlist $wishlist)
    {
        $wishlist->fill( $request->only(['name']) );
        $wishlist->save();

        return new WishlistResource( $wishlist );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Wishlist  $wishlist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wishlist $wishlist)
    {
        Gate::authorize('delete', $wishlist);

        $wishlist->delete();
    }
}
