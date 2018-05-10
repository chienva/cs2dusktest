<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\Users;

class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        // $this->browse(function (Browser $browser) {
        //     $browser->visit('/auth/login')
        //             ->assertSee('メールアドレス');
        // });

        $getUsers = Users::where('id', 6)->first();

        $this->browse(function (Browser $browser) use ($getUsers) {
            $browser->visit('/auth/login')
                    ->type('email', $getUsers->email)
                    ->type('passwd', $getUsers->passwd)
                    ->press('ログイン')
                    ->assertPathIs('/');
        });
    }
}
