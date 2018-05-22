<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use \App\User;
use Tests\Browser\Pages\LoginPage;
use DB,Request;
use Illuminate\Support\Facades\Input;

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
    //         ->assertJson([
    //             'status' => true,
    //         ]);
    //     });
    // }
    // public function testReadMoreArticle()
    // {
    //     $this->browse(function ($browser)  {
    //                 $response = $this->call('GET', $this->urlArticle().'/article/api/get-article/alias/article/?page=1&sort=&_=1524467276735');
    //                 dd( $this->urlArticle().'/article/api/get-article/alias/article/?page=1&sort=&_=1524467276735');
    //     });
    // }
    public function testClickClipInDetailArticle()
    {
        $this->browse(function ($browser) {
            $browser->visit($this->urlArticleDetail());
            $elements  = $browser->attribute('.cs-button-response-d--clip', 'data-state');
            if($elements == 'active'){ //cliped
                $browser->click('.cs-resp-set__item')->waitUntilMissing($elements);
            }
            if($elements !== 'active'){ //not cliped
                $browser->click('.cs-resp-set__item')->waitFor($elements);
            }
            
        });
        
    }
    public function testClickLikeInDetailArticle()
    {
        $this->browse(function ($browser) {
            $browser->visit($this->urlArticleDetail());
            $likeNumber  = $browser->text('.cs-response-c__counter') ? $browser->text('.cs-response-c__counter') : 0;
            $elements  = $browser->attribute('.cs-response-c__button--like', 'data-state');
            $elements2  = $browser->attribute('.cs-response-c__button--like', 'data-csaction');
            var_dump($elements);
            dd($elements2);
            if($elements == 'active'){ //liked
                $browser->click('.cs-response-c__icon--medium')->pause(2000);
                var_dump('liked');
                dd($likeNumber);
                $browser->assertSeeIn('.cs-response-c__counter', ((int)$likeNumber-1) );
            }
            if( empty($elements )){ //not liked
                $browser->click('.cs-response-c__icon--medium')->pause(2000);
                var_dump('not liked');
                dd($likeNumber);
                $browser->assertSeeIn('.cs-response-c__counter', ((int)$likeNumber+1) );
            }
        });
    }

    public function testShowNewEntryInDetailArticle()
    {
        $rawQuery = "SELECT `cs_entry`.* FROM `cs_entry` WHERE (deleted IS NULL) 
        AND (status IN ('2')) AND ((opened IS NULL OR opened <= '2018-05-21 16:07:29')) 
        AND ((closed IS NULL OR closed >= '2018-05-21 16:07:29')) AND (module_id = '5') 
        ORDER BY `created` DESC LIMIT 3";
        $data = DB::select($rawQuery);
        $this->browse(function ($browser)use ($data)  {
            $browser->visit($this->urlArticleDetail())
                    ->assertPathIs($this->urlArticleDetail());
                     // check display 
                    foreach ($data as $dt) {
                        $browser->assertSee($dt->caption);
                    }
        });
    }
    public function testShowRecommendInDetailArticle()
    {
        $rawQuery = "SELECT `e`.`id`, `e`.`caption`, `e`.`img`, `e`.`img_thumbnail`, `e`.`module_id`, count(*) AS `num_count` 
                    FROM `cs_entry` AS `e` LEFT JOIN `cs_entry_view` AS `ev` ON e.id = ev.entry_id 
                    WHERE (e.id <> '1934') AND (e.status = '2') AND (e.deleted is null) 
                    AND (e.opened IS NULL OR e.opened <= '2018-05-21 15:53:05') AND 
                    (e.closed IS NULL OR closed > '2018-05-21 15:53:05') AND (e.module_id = '5') 
                    AND (e.status IN ('1', '2')) GROUP BY `e`.`id` ORDER BY `num_count` DESC, `e`.`opened` DESC, `e`.`id` DESC LIMIT 5";
        $data = DB::select($rawQuery);
        $this->browse(function ($browser)use ($data)  {
            $browser->visit($this->urlArticleDetail())
                    ->assertPathIs($this->urlArticleDetail());
                     // check display 
                    foreach ($data as $dt) {
                        $browser->assertSee($this->truncate($dt->caption, 40));
                    }
        });
    }
    private function truncate($var, $numChars, $stripHtml = true, $suffix = '...') {
        $var = $stripHtml ? strip_tags($var) : $this->getView()->escape($var);
        $encoding = 'UTF-8';
        $len = mb_strlen($var, $encoding);
        return $len <= $numChars ? $var : mb_substr($var, 0, $numChars - mb_strlen($suffix), $encoding) . $suffix;
    }

    public function testShowDataInDetailArticle()
    {
        
        $rawQuery = "SELECT `cs_entry`.* FROM `cs_entry` WHERE (deleted IS NULL) 
                    AND (id = '1934') AND (status = 2) 
                    AND (opened is null or opened <= '2018-05-17 12:49:26') 
                    AND   (closed is null or closed >= '2018-05-17 12:49:26') AND (module_id = '5')";
        $data = DB::select($rawQuery);
        // var_dump($data);die;
        // dd($data->caption);
        $this->browse(function ($browser)use ($data)  {
            $browser->visit($this->urlArticle())
                    ->assertPathIs($this->urlArticle());
                     // check display 
                    $browser->assertSee(!empty($data[0]->caption)?$data[0]->caption:'not found');
                    $browser->assertSee(!empty($data[0]->description)?$data[0]->description:'not found');
        });
        
    }

    public function testShowDataInListArticle()
    {
        $rawQuery = "SELECT `e`.*, `us`.`profile_img`, `us`.`nickname` FROM `cs_entry` AS `e`
                    INNER JOIN `cs_user` AS `us` ON e.user_id = us.id WHERE (e.module_id = '5') AND (e.deleted IS NULL) 
                    AND (e.status = 2) AND (e.opened IS NULL OR e.opened <= '2018-05-16 12:49:06')  AND
                    (e.closed IS NULL OR closed > '2018-05-16 12:49:06') ORDER BY `e`.`opened` DESC, `e`.`id` DESC LIMIT 6";
        $data = DB::select($rawQuery);
        $this->browse(function ($browser)use ($data)  {
            $browser->visit($this->urlArticle())
                    ->assertPathIs($this->urlArticle());
                     // check display 
                    foreach ($data as $dt) {
                        $browser->assertSee($dt->caption);
                        $browser->assertSee($dt->description);
                    }
        });
    }
     public function testCommentWrongArticle()
    {
        $this->browse(function ($browser)  {
            $browser->visit($this->urlArticleDetail())
                    ->press('投稿する')
                    ->assertPathIs($this->urlArticleDetail())
                    ->waitForText('入力必須です');
        });
    }
    public function testCommentArticle()
    {
        $this->browse(function ($browser)  {
            $comment = 'test';
            $browser->visit($this->urlArticleDetail())
                    ->type('description',$comment)
                    ->press('投稿する');
            $this->assertDatabaseHas('cs_entry_comment', [
                'description' => $comment,
                'user_id' => 1,
            ]);
        });
    }
    
    
    public function testDetailArticle()
    {
        // HEADER see title, see back button, click and back
        $this->browse(function ($browser)  {
            $browser->visit($this->urlArticleDetail())
                    ->waitFor('.cs-titlebar__title')
                    ->waitFor('.cs-titlebar__nav-back')
                    ->visit(
                        $browser->attribute('.cs-titlebar__nav-back a', 'href')//usr avater
                    )
                    ->assertPathIs($this->urlArticle());
        });

        // Main Content dropdown
        $this->browse(function ($browser)  {
            $browser->visit($this->urlArticleDetail())
                    ->waitFor('.pm-mgz-details__header-action')
                    // ->select('size', 'Large')
                    // ->click('.pm-mgz-details__header-action')
                    ;//click action dropdown
        });
        // Main Content click user show list user liked
        $this->browse(function ($browser)  {
            $browser->visit($this->urlArticleDetail())
                    ->waitFor('.cs-avatar')
                    ->waitFor('.cs-avatar__name')//user avatar
                    ->visit(
                        $browser->attribute('a.cs-avatar', 'href')//usr avater
                    )
                    ->assertPathIs('/user/home');
        });
        
        // $this->browse(function ($browser)  {
        //     $browser->visit($this->urlArticle())
        //             ->visit(
        //                 $browser->attribute('a.pm-mgz-list-item-body-link', 'href')
        //             );
        //             // ->click('a.pm-mgz-list-item-body-link')
        //             // ->pause(1500)
        //             // ->dump();
        //             // ->waitForText('.pm-mgz-details');
        // });
    }
    

   

}
