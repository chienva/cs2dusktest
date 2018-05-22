<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;
use App\Users;
use Tests\Browser\Components\PostTalkComponent;

class PostTalkPage extends BasePage
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        // return '/talk/api/modal-post/alias/talk';
        return '/talk/talk';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    // public function elements()
    // {
    //     return [
    //         '@element' => '#selector',
    //     ];
    // }

    public function postTalk(Browser $browser)
    {
        $getUsers = Users::where('id', 6)->first();
        $caption = 'this is caption';
        $description = 'this is description';
        $category = 7;
        $img = '5ada3d516dbe2.jpg';
        $tags = 'chienva';

        $browser->visit($this->url())
                ->within(new PostTalkComponent, function ($browser) use ($category, $getUsers, $caption, $description, $img, $tags) {
                        $browser->doPostTalk($category, $getUsers->id, $caption, $description, $img, $tags);
                });
    }
}
