<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\Users;
use App\Modules;
use App\CategoryLarges;
use App\Talks;
use DB;

use Tests\Browser\Pages\LoginPage;
use Tests\Browser\Pages\PostTalkPage;
use Tests\Browser\Components\PostTalkComponent;

class TestListTalk extends DuskTestCase
{
    protected static $_alias = 'talk'; 
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $getModules = Modules::where('alias', self::$_alias)->first();
        $getCategoryLarges = CategoryLarges::where('module_id', $getModules->id)->orderBy('sort_order','asc')->get();

        // $getUsers = Users::where('id', 6)->first();
        // $caption = 'this is caption';
        // $description = 'this is description';

        // $getUsers = Users::where('id', 6)->first();

        $this->browse(function (Browser $browser) use ($getCategoryLarges) {
            $getUsers = Users::where('id', 6)->first();
            $caption = 'this is caption';
            $description = 'this is description';

            $browser->on(new LoginPage)
                    ->loginUser()
                    ->visit('/talk/talk')
                    ->waitFor('.is-login')
                    ->assertSee('TALKに話題を投稿しましょう！')
                    ->click('.cs-titlebar__subtitle');
                
                    // check display menu category header
                    foreach ($getCategoryLarges as $cate) {
                        $browser->assertSee($cate->title);
                    }
            $browser->click('.cs-titlebar__subtitle')
                    ->assertSee('TALKに話題を投稿しましょう！');

            $browser->click('#chienva')
                    ->waitFor('.cs-modal-post')
                    ->assertSee('人気のキーワードから選択')
                    ->within(new PostTalkComponent, function ($browser) use ($caption, $description) {
                        $browser->doPostTalk(6, 7, $caption, $description);
                    });

            // $last = DB::table('cs_entry')->orderBy('id', 'desc')->first();
            $last = Talks::orderBy('id', 'desc')->first();
                    // ->waitUntilMissing('.cs-modal-post');
                    // ->assertUrlIs('/talk/talk/test2/1964/')
                    // ->assertSee('this is caption');
                    // ->waitForLocation('/talk/talk/test2/' . $lastid->id, 1)
            $browser->get('/talk/talk/test2/' . $last->id)
                    ->waitFor('.pm-tlk-board-body')
                    ->assertSee('this is caption');
            // $browser->get('/talk/talk/test2/' . $last->id)
                    // ->assertUrlIs('/test2/' . $last->id);
                    // ->assertSee('this is caption');
        });

        
    }
}
