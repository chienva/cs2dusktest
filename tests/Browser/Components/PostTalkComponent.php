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
            '@moduleId' => 'input[name=module_id]',
            '@category' => 'select[name=category_l_id]',
            '@caption' => 'input[name=caption]',
            '@description' => 'textarea[name=description]',
        ];
    }

    public function doPostTalk($browser, $moduleId, $category, $caption, $description)
    {
        $browser->select('@category', $category)
                ->type('@moduleId', $moduleId)
                ->type('@caption', $caption)
                ->type('@description', $description)
                ->press('投稿する');
                // ->assertDialogOpened('システムエラーが発生しました。');
                // ->acceptDialog();
        // $browser->driver->switchTo()->alert()->accept();
    }
}
