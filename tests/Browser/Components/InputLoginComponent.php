<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class InputLoginComponent extends BaseComponent
{
    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return '.pm-lgn__content';
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
            '@email' => 'input[name=email]',
            '@passwd' => 'input[name=passwd]',
        ];
    }

    public function doLogin($browser, $email, $passwd, $redirectUrl)
    {
        $browser->assertSee('メールアドレス')
            ->assertVisible('#email')
            ->assertSee('パスワード')
            ->assertVisible('#passwd')
            ->assertSee('次回から自動ログイン')
            ->assertVisible('.left20')
            ->assertVisible('.cs-button-a--1-l')
            ->assertSee('パスワードを忘れた場合')
            ->assertVisible('.cs-button-b--1-l')
            ->assertSee('または')
            ->assertVisible('.pm-lgn-sns .pm-lgn-sns__heading')
            ->assertSee('SNSアカウントでログインする')
            ->assertVisible('.pm-lgn-sns__link-tw')
            ->assertSee('Twitter アカウントを使用')
            ->assertVisible('.pm-lgn-sns__link-fb')
            ->assertSee('Facebook アカウントを使用');

        $browser->type('@email', '')
            ->type('@passwd', '')
            ->press('ログイン')
            ->assertSee('入力必須です。');

        $browser->type('@email', $email)
            ->type('@passwd', '')
            ->press('ログイン')
            ->assertSee('入力必須です。');

        $browser->type('@email', 'staff.newnomori+8@vcxvcxgmail.com')
            ->type('@passwd', $passwd)
            ->press('ログイン')
            ->assertSee('ログインに失敗しました。');

        $browser->type('@email', $email)
            ->type('@passwd', $passwd)
            ->assertInputValue('@email', $email)
            ->assertInputValue('@passwd', $passwd)
            ->press('ログイン')
            // ->assertPathIs($redirectUrl);
            ->visit($redirectUrl);

    }
}
