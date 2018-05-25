<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;

class DetailTalkPage extends BasePage
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/talk/talk/test2/' . $this->id;
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
    public function elements()
    {
        return [
            '@element' => '#selector',
        ];
    }

    // public function detailTalkAction(Browser $browser)
    // {
    //     $browser->visit($this->url())
    //             ->assertSee('写真を追加する');
    // }
}
