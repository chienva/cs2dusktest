<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page as BasePage;
use App\Users;
use Tests\Browser\Components\InputLoginComponent;

class LoginPage extends BasePage
{
    protected $redirectUrl;

    public function __construct($redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
    }

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/auth/login';
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */

    public function loginUser(Browser $browser)
    {
        $getUsers = Users::where('id', 6)->first();

        $browser->visit($this->url())
                ->within(new InputLoginComponent, function ($browser) use ($getUsers) {
                        $browser->doLogin($getUsers->email, $getUsers->passwd, $this->redirectUrl);
                });
    }
}
