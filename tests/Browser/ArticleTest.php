<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use \App\User;
use Tests\Browser\Pages\LoginPage;
use DB;

class ArticleTest extends DuskTestCase
{
     public function urlArticle()
    {
        return '/article/article/';
    }
     public function urlArticleDetail()
    {
        return '/article/article/1934/';
    }
    public function urlLogin()
    {
        return '/auth/do-login';
    }
    public function urlUserDetail()
    {
        return '/user/profile/1/';
    }

     public function testNotLogged()
    {
        $this->browse(function ($browser)  {
            $browser->visit($this->urlArticle())
                    ->assertSee('ログイン');
        });
    }

    public function testListArticle()
    {
        
        $this->browse(function ($browser)  {
            $browser->visit(new LoginPage)
                ->loginUser('');
        });
        
        $this->browse(function ($browser)  {
            $browser->visit($this->urlArticle())
                    ->assertPathIs($this->urlArticle())
                    ->waitFor('.pm-mgz-list-item');
        });
        
    }
    // public function testShowMoreArticle()
    // {
    //     $this->browse(function ($browser)  {
    //         $browser->visit($this->urlArticleDetail())
    //         ->click('.cs-cmt-thread-more__label')
    //         ->seeJson([
    //             'status' => true,
    //         ]);
    //     });
    //     //  $this->get('/article/api/get-comment/alias/article/id/1934?last_comment_id=2415&next_page=2&order=&_=1526349767479', ['' => ''])
    //     //  ->seeJsonEquals([
    //     //      'status' => true,
    //     //  ]);
    // }
    // public function testReadMoreArticle()
    // {
    //     $this->browse(function ($browser)  {
    //                 $response = $this->call('GET', $this->urlArticle().'/article/api/get-article/alias/article/?page=1&sort=&_=1524467276735');
    //                 dd( $this->urlArticle().'/article/api/get-article/alias/article/?page=1&sort=&_=1524467276735');
    //     });
    // }
    public function testShowDataInListArticle()
    {
         
        $rawQuery = "SELECT `e`.*, `us`.`profile_img`, `us`.`nickname` FROM `cs_entry` AS `e`
                    INNER JOIN `cs_user` AS `us` ON e.user_id = us.id WHERE (e.module_id = '5') AND (e.deleted IS NULL) 
                    AND (e.status = 2) AND (e.opened IS NULL OR e.opened <= '2018-05-16 12:49:06') AND
                    (e.closed IS NULL OR closed > '2018-05-16 12:49:06') ORDER BY `e`.`opened` DESC, `e`.`id` DESC LIMIT 10";

        $data = DB::select($rawQuery);
        var_dump($data);die;
        $this->browse(function ($browser)  {
            $browser->visit($this->urlArticle())
                    ->assertPathIs($this->urlArticle())
                    ->assertSee(1);//number of like
        });
    }
    //  public function testCommentWrongArticle()
    // {
    //     $this->browse(function ($browser)  {
    //         $browser->visit($this->urlArticleDetail())
    //                 ->press('投稿する')
    //                 ->assertPathIs($this->urlArticleDetail())
    //                 ->waitForText('入力必須です');
    //     });
    // }
    // public function testCommentArticle()
    // {
    //     $this->browse(function ($browser)  {
    //         $comment = 'test';
    //         $browser->visit($this->urlArticleDetail())
    //                 ->type('description',$comment)
    //                 ->press('投稿する');
    //         $this->assertDatabaseHas('cs_entry_comment', [
    //             'description' => $comment,
    //             'user_id' => 1,
    //         ]);
    //     });
    // }
    
    
    // public function testDetailArticle()
    // {
    //     // HEADER see title, see back button, click and back
    //     $this->browse(function ($browser)  {
    //         $browser->visit($this->urlArticleDetail())
    //                 ->waitFor('.cs-titlebar__title')
    //                 ->waitFor('.cs-titlebar__nav-back')
    //                 ->visit(
    //                     $browser->attribute('.cs-titlebar__nav-back a', 'href')//usr avater
    //                 )
    //                 ->assertPathIs($this->urlArticle());
    //     });

    //     // Main Content dropdown
    //     $this->browse(function ($browser)  {
    //         $browser->visit($this->urlArticleDetail())
    //                 ->waitFor('.pm-mgz-details__header-action')
    //                 // ->select('size', 'Large')
    //                 // ->click('.pm-mgz-details__header-action')
    //                 ;//click action dropdown
    //     });
    //     // Main Content click user show list user liked
    //     $this->browse(function ($browser)  {
    //         $browser->visit($this->urlArticleDetail())
    //                 ->waitFor('.cs-avatar')
    //                 ->waitFor('.cs-avatar__name')//user avatar
    //                 ->visit(
    //                     $browser->attribute('a.cs-avatar', 'href')//usr avater
    //                 )
    //                 ->assertPathIs('/user/home');
    //     });
        
    //     // $this->browse(function ($browser)  {
    //     //     $browser->visit($this->urlArticle())
    //     //             ->visit(
    //     //                 $browser->attribute('a.pm-mgz-list-item-body-link', 'href')
    //     //             );
    //     //             // ->click('a.pm-mgz-list-item-body-link')
    //     //             // ->pause(1500)
    //     //             // ->dump();
    //     //             // ->waitForText('.pm-mgz-details');
    //     // });
    // }
    

   

}
