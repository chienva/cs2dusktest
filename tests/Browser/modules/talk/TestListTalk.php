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
use Tests\Browser\Pages\DetailTalkPage;
use Tests\Browser\Components\PostTalkComponent;

use DateTime;

class TestListTalk extends DuskTestCase
{
    protected static $_alias = 'talk'; 
    protected static $_url = '/talk/talk';

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testListTalks()
    {
        $getModules = Modules::where('alias', self::$_alias)->first();
        $getCategoryLarges = CategoryLarges::where('module_id', $getModules->id)->orderBy('sort_order','asc')->get();

        $this->browse(function (Browser $browser) use ($getCategoryLarges, $getModules) {
            $options = $this->getOption($getModules, 'is_post');

            $talks = $this->getTalks();
            $countTalk = $this->getTalksAll();
            
            $browser->on(new LoginPage(self::$_url))
                    ->loginUser()
                    ->waitFor('.is-login')
                    ->waitFor('.cs-titlebar__title')
                    ->assertSee($getModules->caption)
                    ->click('.cs-titlebar__subtitle');
                
                    // check display menu category header
                    foreach ($getCategoryLarges as $cate) {
                        $browser->assertSee($cate->title);
                    }

            $browser->click('.cs-titlebar__subtitle');
            if ($options) {
                $captionPost = $getModules->caption . 'に話題を投稿しましょう！';
                $browser->assertSee($captionPost);
            }
            
            $browser->assertSee('更新順')
                    ->assertSee('コメント数順');

            if ($countTalk > 10) {
                $browser->assertSee('もっと見る');
            }

            //check list
            $this->assertNotNull($talks);
            foreach ($talks as $talk) {
                // $dateTalk = $talk->opened ?: $talk->created;
                // $dateTalkFormat = $this->dateFormat($dateTalk);

                $browser->assertSee($talk->caption);
                $browser->assertSee($talk->description);
                $browser->assertSee($talk->nickname);
                $browser->assertValue('.cs-response-c__button--like .cs-response-c__counter', $talk->num_good);
                $browser->assertValue('.cs-response-c__button--comment .cs-response-c__counter', $talk->num_comment);
                // $browser->assertSee($dateTalkFormat);
            }
            
        });

        
    }

    // public function testShowListTalks()
    // {
    //     $talks = $this->getTalks();
    //     $countTalk = $this->getTalksAll();

    //     $this->browse(function (Browser $browser) use ($talks) {
    //         $browser->visit('/talk/talk')
    //                 ->assertSee('TALKに話題を投稿しましょう！');

    //         // check list
    //         $this->assertNotNull($talks);
    //         foreach ($talks as $talk) {
    //             $browser->assertSee($talk->caption);
    //             $browser->assertSee($talk->description);
    //             $browser->assertSee($talk->nickname);
    //         }
            
    //     });
    // }

    public function getOption($dataOption, $key, $nothingValue = false) {
        $options = json_decode($dataOption->options, true);
        return isset($options[$key]) ? $options[$key] : $nothingValue;
    }


    public function getTalksAll() {
        $talks = DB::table('cs_entry')
                    ->join('cs_user', 'cs_user.id', '=', 'cs_entry.user_id')
                    ->select('cs_entry.*', 'cs_user.profile_img', 'cs_user.nickname')
                    ->whereNull('cs_entry.deleted')
                    ->whereNull('cs_entry.latest_commented')
                    ->whereIn('cs_entry.status', [1, 2])
                    ->where('cs_entry.module_id', 1)
                    ->orderBy('cs_entry.created', 'desc')
                    ->get();

        return count($talks);
    }

    public function getTalks() {
        $talks = DB::table('cs_entry')
                    ->join('cs_user', 'cs_user.id', '=', 'cs_entry.user_id')
                    ->select('cs_entry.*', 'cs_user.profile_img', 'cs_user.nickname')
                    ->whereNull('cs_entry.deleted')
                    ->whereNull('cs_entry.latest_commented')
                    ->whereIn('cs_entry.status', [1, 2])
                    ->where('cs_entry.module_id', 1)
                    ->orderBy('cs_entry.created', 'desc')
                    ->limit(10)
                    ->get();

        return $talks;
    }

    // public function testClickSortNewList()
    // {
    //     $talks = DB::table('cs_entry')
    //                 ->join('cs_user', 'cs_user.id', '=', 'cs_entry.user_id')
    //                 ->select('cs_entry.*', 'cs_user.profile_img', 'cs_user.nickname')
    //                 ->whereNull('cs_entry.deleted')
    //                 ->whereNull('cs_entry.latest_commented')
    //                 ->whereIn('cs_entry.status', [1, 2])
    //                 ->where('cs_entry.module_id', 1)
    //                 ->orderBy('cs_entry.created', 'desc')
    //                 // ->offset(10)
    //                 ->limit(10)
    //                 ->get();

    //     $this->browse(function (Browser $browser) use ($talks) {
    //         $browser->visit('/talk/talk')
    //                 ->click('#sort_new')
    //                 ->visit('/talk/talk?sort=new');
                    
    //         // echo $browser->driver->getCurrentURL();
    //         // $browser->pause(1000)->assertPathIs('/talk/talk?sort=new');
    //         // check list
    //         $this->assertNotNull($talks);
    //         foreach ($talks as $talk) {
    //             $browser->assertSee($talk->caption);
    //             $browser->assertSee($talk->description);
    //             $browser->assertSee($talk->nickname);
    //         }
            
    //     });
    // }

    // public function testClickSortCommentList()
    // {
    //     $talks = DB::table('cs_entry')
    //                 ->join('cs_user', 'cs_user.id', '=', 'cs_entry.user_id')
    //                 ->select('cs_entry.*', 'cs_user.profile_img', 'cs_user.nickname')
    //                 ->whereNull('cs_entry.deleted')
    //                 ->whereIn('cs_entry.status', [1, 2])
    //                 ->where('cs_entry.module_id', 1)
    //                 ->orderBy('cs_entry.num_comment', 'desc')
    //                 ->limit(10)
    //                 ->get();

    //     $this->browse(function (Browser $browser) use ($talks) {
    //         $browser->visit('/talk/talk')
    //                 ->click('#sort_comment')
    //                 // ->assertPathIs('/talk/talk?sort=comment')
    //                 ->waitFor('.with-comment');

    //         // check list
    //         $this->assertNotNull($talks);
    //         foreach ($talks as $talk) {
    //             $browser->assertSee($talk->caption);
    //             $browser->assertSee($talk->description);
    //             $browser->assertSee($talk->nickname);
    //         }
            
    //     });
    // }


    // public function testClickViewDetail()
    // {
    //     $talks = DB::table('cs_entry')
    //                 ->join('cs_user', 'cs_user.id', '=', 'cs_entry.user_id')
    //                 ->select('cs_entry.*', 'cs_user.profile_img', 'cs_user.nickname')
    //                 ->whereNull('cs_entry.deleted')
    //                 ->whereNull('cs_entry.latest_commented')
    //                 ->whereIn('cs_entry.status', [1, 2])
    //                 ->where('cs_entry.module_id', 1)
    //                 ->orderBy('cs_entry.created', 'desc')
    //                 ->limit(10)
    //                 ->get();

    //     $this->browse(function (Browser $browser) use ($talks) {
    //         $browser->visit('/talk/talk');
    //                 // ->assertSee('TALKに話題を投稿しましょう！')
    //                 // ->visit(
    //                 //     $browser->attribute('#chienva2041', 'href')
    //                 // );
    //                 // ->assertPathIs('/talk/talk/test2/2041/');
                    
    //         $this->assertNotNull($talks);
    //         foreach ($talks as $talk) {
    //             $idSelector = '#chienva'.$talk->id;
    //             $attribute = $browser->attribute($idSelector, 'href');
    //             // echo $attribute;

    //             // $browser->assertVisible($idSelector)
    //             //         ->visit($attribute)
    //             //         ->assertSee($talk->caption);
    //                     // ->assertSee($talk->caption);

    //             $browser->assertVisible($idSelector)
    //                     ->visit(
    //                         $browser->attribute($idSelector, 'href')
    //                     )
    //                     ->assertPathIs($attribute);
    //                     // ->clickLink('Edit')
    //                     // ->type('description', 'Testing it with dusk again')
    //                     // ->press('Update')
    //                     // ->assertPathIs('/todoapplaravel/public/todo/1');

    //         }

            
            
    //     });
    // }

    // public function testPostTalk()
    // {
    //     $this->browse(function (Browser $browser) {
    //         $getUsers = Users::where('id', 6)->first();
    //         $caption = 'this is caption for test 1';
    //         $description = 'this is description for test 1';
    //         $category = 7;
    //         $moduleId = 1;
    //         $img = '5ada3d516dbe2.jpg';
    //         $tags = 'chienva';

    //         $browser->visit('/talk/talk')
    //                 ->assertSee('TALKに話題を投稿しましょう！')
    //                 ->click('.pm-tlk-promote__button-icon')
    //                 ->waitFor('.cs-modal-post')
    //                 ->assertSee('人気のキーワードから選択')
    //                 ->within(new PostTalkComponent, function ($browser) use ($moduleId, $category, $caption, $description) {
    //                     $browser->doPostTalk($moduleId, $category, $caption, $description);
    //                 });

    //         $last = Talks::orderBy('id', 'desc')->first();
    //         $browser->visit(new DetailTalkPage($last->id))
    //                 ->assertSee($caption);
            
    //     });
    // }
}
