<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Users;
use Tests\Browser\Pages\LoginPage;
use Tests\Browser\Pages\CreateCs2Page;

class TestCommentSuccess extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */

    public function testExample()
    {
        $this->browse(function ($browser) {
            $browser->on(new LoginPage)
                    ->loginUser()
                    ->visit('/talk/talk')
                    ->waitFor('.is-login')
                    ->assertSee('TALKに話題を投稿しましょう！');
        });
    }

}
