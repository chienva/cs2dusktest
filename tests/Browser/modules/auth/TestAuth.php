<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\Users;
use Tests\Browser\Pages\LoginPage;

class TestAuth extends DuskTestCase
{
    public function testAuthSuccess()
    {
        $getUsers = Users::where('id', 6)->first();
        $redirectUrl = '/talk/talk';

        $this->browse(function (Browser $browser) use ($getUsers, $redirectUrl) {
            $browser->on(new LoginPage($redirectUrl))
                    ->loginUser()
                    ->waitFor('.is-login')
                    ->assertSee($getUsers->nickname)
                    ->click('.cs-h-toolbar-item--user')
                    ->waitFor('.cs-h-toolbar-menu--user')
                    ->assertSee('ログアウト');
            
        });        
    }

}
