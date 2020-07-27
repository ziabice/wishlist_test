<?php

use App\User;
use App\Wishlist;
use App\WishlistItem;
use Illuminate\Database\Seeder;

class WishlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Make some users and seed the DB
        $user1 = factory(User::class);
        $wishlist = factory(Wishlist::class)->create([
            'user_id' => $user1
        ]);
        factory(WishlistItem::class, 5)->create([
            'wishlist_id' => $wishlist
        ]);

        $user2 = factory(User::class);
        factory(Wishlist::class)->create([
            'user_id' => $user2
        ]);

        $user3 = factory(User::class);
        $wishlist2 = factory(Wishlist::class)->create([
            'user_id' => $user3
        ]);
        factory(WishlistItem::class, 3)->create([
            'wishlist_id' => $wishlist2
        ]);
    }
}
