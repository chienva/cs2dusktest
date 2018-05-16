<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

use App\Talks;

class PostTalkComponent extends BaseComponent
{
    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return '#cs-post-form';
    }

    /**
     * Assert that the browser page contains the component.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertVisible($this->selector());
    }

    /**
     * Get the element shortcuts for the component.
     *
     * @return array
     */    
    public function elements()
    {
        return [
            '@category' => 'select[name=category_l_id]',
            '@caption' => 'input[name=caption]',
            '@description' => 'textarea[name=description]',
        ];
    }

    public function doPostTalk($browser, $user_id, $category, $caption, $description)
    {
        $browser->select('@category', $category)
                ->type('@caption', $caption)
                ->type('@description', $description)
                ->press('投稿する');
                // ->visit('/talk/api/do-post/alias/talk')
                // ->assertSee()

                // ->assertUrlIs('/talk/talk/business/1949/')
                // ->assertPathIs('/home/' . $contacts->first()->id)
                // ->assertSee($caption);
    }
}
