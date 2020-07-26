<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Wishlist;
use App\WishlistItem;
use App\User;
use Laravel\Sanctum\Sanctum;

class WishlistItemsTest extends TestCase
{
    use RefreshDatabase;

    const API_URL = '/api/v1/';

    /** @test */
    public function only_the_owner_can_add_items()
    {
        $owner = factory(User::class)->create();
        $wishlist = factory(Wishlist::class)->create([
            'user_id' => $owner
        ]);

        $user = factory(User::class)->create();

        $response = $this->postJson(
            self::API_URL . 'wishlist/' . $wishlist->getKey() . '/item/create',
            [
                'name' => 'new item'
            ]
        );

        $response->assertUnauthorized();

        Sanctum::actingAs($user);
        $response = $this->postJson(
            self::API_URL . 'wishlist/' . $wishlist->getKey() . '/item/create',
            [
                'name' => 'new item'
            ]
        );

        $response->assertForbidden();

        Sanctum::actingAs($owner);
        $response = $this->postJson(
            self::API_URL . 'wishlist/' . $wishlist->getKey() . '/item/create',
            [
                'name' => 'new item'
            ]
        );

        $response->assertCreated();
    }

    /** @test */
    public function wishlist_items_are_validated()
    {
        $owner = factory(User::class)->create();
        $wishlist = factory(Wishlist::class)->create([
            'user_id' => $owner
        ]);
        
        $wishlist2 = factory(Wishlist::class)->create([
            'user_id' => $owner
        ]);

        Sanctum::actingAs($owner);

        $response = $this->postJson(
            self::API_URL . 'wishlist/' . $wishlist->getKey() . '/item/create',
            [
                'name' => ''
            ]
        );

        $response->assertJsonValidationErrors(['name']);

        $response = $this->postJson(
            self::API_URL . 'wishlist/' . $wishlist->getKey() . '/item/create',
            [
                'name' => '     '
            ]
        );

        $response->assertJsonValidationErrors(['name']);

        $response = $this->postJson(
            self::API_URL . 'wishlist/' . $wishlist->getKey() . '/item/create',
            [
                'name' => str_repeat('a', 200)
            ]
        );

        $response->assertJsonValidationErrors(['name']);

        $response = $this->postJson(
            self::API_URL . 'wishlist/' . $wishlist->getKey() . '/item/create',
            [
                'name' => 'Item #1'
            ]
        );

        $response->assertCreated();

        $response->assertJson([
            'data' => [
                'wishlist_id' => $wishlist->getKey(),
                'name' => 'Item #1'
            ]
        ]);

        $response = $this->postJson(
            self::API_URL . 'wishlist/' . $wishlist->getKey() . '/item/create',
            [
                'name' => 'Item #2'
            ]
        );

        $response->assertCreated();

        $response->assertJson([
            'data' => [
                'wishlist_id' => $wishlist->getKey(),
                'name' => 'Item #2'
            ]
        ]);

        $wishlist->refresh();
        $this->assertEquals(2, $wishlist->items()->count() );
        $this->assertEquals(0, $wishlist2->items()->count() );

        $response = $this->getJson(
            self::API_URL . 'wishlist/' . $wishlist->getKey()
        );
        $response->assertJsonCount(2, 'data.items');

        $response = $this->getJson(
            self::API_URL . 'wishlist/' . $wishlist2->getKey()
        );
        $response->assertJsonCount(0, 'data.items');

    }

    /** @test */
    public function only_the_owner_can_edit_an_item()
    {
        $owner = factory(User::class)->create();
        $wishlist = factory(Wishlist::class)->create([
            'user_id' => $owner
        ]);

        $user = factory(User::class)->create();

        $item1 = factory(WishlistItem::class)->create([
            'wishlist_id' => $wishlist
        ]);

        $response = $this->patchJson(
            self::API_URL . 'wishlist/' . $wishlist->getKey() . '/item/' . $item1->getKey(),
            [
                'name' => 'Updated item'
            ]
        );

        $response->assertUnauthorized();

        Sanctum::actingAs($user);
        $response = $this->patchJson(
            self::API_URL . 'wishlist/' . $wishlist->getKey() . '/item/' . $item1->getKey(),
            [
                'name' => 'Updated item'
            ]
        );

        $response->assertForbidden();

        Sanctum::actingAs($owner);
        $response = $this->patchJson(
            self::API_URL . 'wishlist/' . $wishlist->getKey() . '/item/' . $item1->getKey(),
            [
                'name' => 'Updated item'
            ]
        );

        $response->assertSuccessful();

        $response->assertJson([
            'data' => [
                'id' => $item1->getKey(),
                'name' => 'Updated item'
            ]
        ]);

        $item1->refresh();
        $this->assertEquals('Updated item', $item1->name);

    }

    /** @test */
    public function edited_wishlist_items_are_validated()
    {
        $owner = factory(User::class)->create();
        $wishlist = factory(Wishlist::class)->create([
            'user_id' => $owner
        ]);
        
        $item1 = factory(WishlistItem::class)->create([
            'wishlist_id' => $wishlist
        ]);

        Sanctum::actingAs($owner);

        $response = $this->patchJson(
            self::API_URL . 'wishlist/' . $wishlist->getKey() . '/item/' . $item1->getKey(),
            [
                'name' => ''
            ]
        );

        $response->assertJsonValidationErrors(['name']);

        $response = $this->patchJson(
            self::API_URL . 'wishlist/' . $wishlist->getKey() . '/item/' . $item1->getKey(),
            [
                'name' => '     '
            ]
        );

        $response->assertJsonValidationErrors(['name']);

        $response = $this->patchJson(
            self::API_URL . 'wishlist/' . $wishlist->getKey() . '/item/' . $item1->getKey(),
            [
                'name' => str_repeat('a', 200)
            ]
        );

        $response->assertJsonValidationErrors(['name']);

        $response = $this->patchJson(
            self::API_URL . 'wishlist/' . $wishlist->getKey() . '/item/' . $item1->getKey(),
            [
                'name' => 'Updated Item'
            ]
        );

        $response->assertSuccessful();

        $response->assertJson([
            'data' => [
                'wishlist_id' => $wishlist->getKey(),
                'name' => 'Updated Item'
            ]
        ]);

    }

    /** @test */
    public function only_the_owner_can_delete_a_wishlist_item()
    {
        $owner = factory(User::class)->create();
        $wishlist = factory(Wishlist::class)->create([
            'user_id' => $owner
        ]);

        $wishlist2 = factory(Wishlist::class)->create([
            'user_id' => $owner
        ]);
        
        $item1 = factory(WishlistItem::class)->create([
            'wishlist_id' => $wishlist
        ]);

        $item2 = factory(WishlistItem::class)->create([
            'wishlist_id' => $wishlist
        ]);

        $item3 = factory(WishlistItem::class)->create([
            'wishlist_id' => $wishlist2
        ]);

        $user = factory(User::class)->create();

        $response = $this->deleteJson( self::API_URL . 'wishlist/' . $wishlist->getKey() . '/item/' . $item1->getKey() );
        $response->assertUnauthorized();

        Sanctum::actingAs($user);

        $response = $this->deleteJson( self::API_URL . 'wishlist/' . $wishlist->getKey() . '/item/' . $item1->getKey() );
        $response->assertForbidden();

        Sanctum::actingAs($owner);

        // Use a wrong item ID
        $response = $this->deleteJson( self::API_URL . 'wishlist/' . $wishlist->getKey() . '/item/' . $item3->getKey() );
        $response->assertForbidden();

        $response = $this->deleteJson( self::API_URL . 'wishlist/' . $wishlist->getKey() . '/item/' . $item1->getKey() );
        $response->assertSuccessful();

        $wishlist->refresh();
        $this->assertEquals(1, $wishlist->items->count());

        $response = $this->deleteJson( self::API_URL . 'wishlist/' . $wishlist->getKey() . '/item/' . $item1->getKey() );
        $response->assertNotFound();
        
    }
}
