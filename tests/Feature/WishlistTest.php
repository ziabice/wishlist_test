<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Wishlist;
use App\WishlistItem;
use Laravel\Sanctum\Sanctum;

class WishlistTest extends TestCase
{
    use RefreshDatabase;

    const API_URL = '/api/v1/';

    /** @test */
    public function only_the_owner_can_get_a_wishlist()
    {
        $owner = factory(User::class)->create();
        $wishlist = factory(Wishlist::class)->create([
            'user_id' => $owner
        ]);

        $user = factory(User::class)->create();

        $wishlist2 = factory(Wishlist::class)->create([
            'user_id' => $user
        ]);

        $response = $this->getJson( self::API_URL . 'wishlist/' . $wishlist->getKey() );
        $response->assertUnauthorized();

        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson( self::API_URL . 'wishlist/' . $wishlist->getKey() );
        $response->assertForbidden();

        Sanctum::actingAs($owner, ['*']);

        $response = $this->getJson( self::API_URL . 'wishlist/' . $wishlist->getKey() );
        $response->assertSuccessful();
        
        $response->assertJson([
            'data' => [
                'user_id' => $owner->getKey()
            ]
        ]);

    }

    /** @test */
    public function only_the_owner_can_get_the_wishlists()
    {
        $owner_wishlist_count = 10;
        $user_wishlist_count = 5;

        $owner = factory(User::class)->create();
        factory(Wishlist::class, $owner_wishlist_count)->create([
            'user_id' => $owner
        ]);

        $user = factory(User::class)->create();

        factory(Wishlist::class, $user_wishlist_count)->create([
            'user_id' => $user
        ]);

        Sanctum::actingAs($owner, ['*']);

        $response = $this->getJson( self::API_URL . 'wishlists' );
        $response->assertSuccessful();
        $response->assertJsonCount($owner_wishlist_count, 'data');

        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson( self::API_URL . 'wishlists' );
        $response->assertSuccessful();
        $response->assertJsonCount($user_wishlist_count, 'data');

        $user2 = factory(User::class)->create();
        Sanctum::actingAs($user2, ['*']);

        $response = $this->getJson( self::API_URL . 'wishlists' );
        $response->assertSuccessful();
        $response->assertJsonCount(0, 'data');
        
    }

    /** @test */
    public function only_authenticated_user_can_create_wishlist()
    {
        $user = factory(User::class)->create();
        
        $response = $this->postJson( 
            self::API_URL . 'wishlist',
            [
                'name' => 'Wishlist name'
            ]
        );

        $response->assertUnauthorized();

        $user = factory(User::class)->create();
        
        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson( 
            self::API_URL . 'wishlist',
            [
                'name' => 'Wishlist name'
            ]
        );

        $response->assertCreated();

    }

    /** @test */
    public function wishlist_creation_must_validate_input_data()
    {
        $user = factory(User::class)->create();
        
        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson( 
            self::API_URL . 'wishlist',
            [
                'name' => ''
            ]
        );

        $response->assertJsonValidationErrors('name');

        $response = $this->postJson( 
            self::API_URL . 'wishlist',
            [
                'name' => '     '
            ]
        );

        $response->assertJsonValidationErrors('name');

        $response = $this->postJson( 
            self::API_URL . 'wishlist',
            [
                'name' => str_repeat('a', 300)
            ]
        );

        $response->assertJsonValidationErrors('name');


        $response = $this->postJson( 
            self::API_URL . 'wishlist',
            [
                'name' => 'Valid name'
            ]
        );
        $response->assertCreated();

        $response->assertJson([
            'data' => [
                'name' => 'Valid name',
                'user_id' => $user->getKey()
            ]
        ]);
    }

    /** @test */
    public function only_authenticated_user_can_edit_a_wishlist()
    {
        $user = factory(User::class)->create();
        $wishlist = factory(Wishlist::class)->create([
            'user_id' => $user
        ]);
        
        $response = $this->patchJson( 
            self::API_URL . 'wishlist/' . $wishlist->getKey(),
            [
                'name' => 'Updated name'
            ]
        );

        $response->assertUnauthorized();
        
        Sanctum::actingAs($user, ['*']);

        $response = $this->patchJson( 
            self::API_URL . 'wishlist/' .  $wishlist->getKey(),
            [
                'name' => 'Updated name'
            ]
        );

        $response->assertSuccessful();

    }

    /** @test */
    public function only_owner_user_can_edit_a_wishlist()
    {
        $owner = factory(User::class)->create();
        $wishlist = factory(Wishlist::class)->create([
            'user_id' => $owner
        ]);

        $user = factory(User::class)->create();
        
        Sanctum::actingAs($user, ['*']);
        
        $response = $this->patchJson( 
            self::API_URL . 'wishlist/' . $wishlist->getKey(),
            [
                'name' => 'Updated name'
            ]
        );

        $response->assertForbidden();
        
        Sanctum::actingAs($owner, ['*']);
        $response = $this->patchJson( 
            self::API_URL . 'wishlist/' .  $wishlist->getKey(),
            [
                'name' => 'Updated name'
            ]
        );

        $response->assertSuccessful();

    }

    /** @test */
    public function wishlist_editing_must_validate_input_data()
    {
        $owner = factory(User::class)->create();
        $wishlist = factory(Wishlist::class)->create([
            'user_id' => $owner
        ]);
        
        Sanctum::actingAs($owner, ['*']);

        $response = $this->patchJson( 
            self::API_URL . 'wishlist/' .  $wishlist->getKey(),
            [
                'name' => ''
            ]
        );

        $response->assertJsonValidationErrors('name');

        $response = $this->patchJson( 
            self::API_URL . 'wishlist/' .  $wishlist->getKey(),
            [
                'name' => '     '
            ]
        );

        $response->assertJsonValidationErrors('name');

        $response = $this->patchJson( 
            self::API_URL . 'wishlist/' .  $wishlist->getKey(),
            [
                'name' => str_repeat('a', 300)
            ]
        );

        $response->assertJsonValidationErrors('name');


        $response = $this->patchJson( 
            self::API_URL . 'wishlist/' .  $wishlist->getKey(),
            [
                'name' => 'Updated name'
            ]
        );
        $response->assertSuccessful();

        $response->assertJson([
            'data' => [
                'name' => 'Updated name',
                'user_id' => $owner->getKey()
            ]
        ]);
    }

    /** @test */
    public function only_the_owner_can_delete_a_wishlist()
    {
        $owner = factory(User::class)->create();
        $wishlist = factory(Wishlist::class)->create([
            'user_id' => $owner
        ]);

        $user = factory(User::class)->create();

        $wishlist2 = factory(Wishlist::class)->create([
            'user_id' => $user
        ]);

        $response = $this->deleteJson(
            self::API_URL . 'wishlist/' .  $wishlist->getKey()
        );

        $response->assertUnauthorized();
        
        Sanctum::actingAs($owner, ['*']);

        $response = $this->deleteJson(
            self::API_URL . 'wishlist/' .  $wishlist2->getKey()
        );

        $response->assertForbidden();

        Sanctum::actingAs($user, ['*']);

        $response = $this->deleteJson(
            self::API_URL . 'wishlist/' .  $wishlist->getKey()
        );

        $response->assertForbidden();

        Sanctum::actingAs($owner, ['*']);

        $response = $this->deleteJson(
            self::API_URL . 'wishlist/' .  $wishlist->getKey()
        );

        $response->assertSuccessful();

        $deleted_wishlist = Wishlist::find($wishlist->getKey());
        $this->assertNull( $deleted_wishlist );

        $response = $this->deleteJson(
            self::API_URL . 'wishlist/' .  $wishlist->getKey()
        );

        $response->assertNotFound();

    }
}
