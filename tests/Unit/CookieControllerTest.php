<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\CookieController;
use App\Models\Cookie;
use Illuminate\Support\Facades\Validator;
use Predis\Client;

class CookieControllerTest extends TestCase
{

   /** @test */
   public function it_can_store_a_new_cookie()
   {
       $cookieController = new CookieController();

       $data = [
           'title' => 'Test Cookie',
           'description' => 'Test Description',
       ];

       $response = $cookieController->store(new \Illuminate\Http\Request($data));
    //    dump("Response / Create : {$response->getStatusCode()}");

       $this->assertEquals(302, $response->getStatusCode()); 
       
       $this->assertDatabaseHas('cookies', $data);
   }

   /** @test */
   public function it_can_delete_an_existing_cookie()
   {
       $cookie = Cookie::factory()->create();

       $cookieController = new CookieController();

       $response = $cookieController->destroy($cookie->id);
    //    dump("Response / Delete : {$response->getStatusCode()}");

       $this->assertEquals(302, $response->getStatusCode()); 
       
       $this->assertDatabaseMissing('cookies', ['id' => $cookie->id]);
   }
}
