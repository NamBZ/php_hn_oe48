<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A basic setup before excute test.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:refresh');
        $this->artisan('db:seed --class=UserSeeder');
    }

    public function testLoginScreenCanBeRendered()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSee(__('Login'))
                ->assertSee(__('Register'))
                ->assertPresent('input[name="email"]')
                ->assertPresent('input[name="password"]')
                ->assertPresent('input[name="password"]')
                ->assertPresent('button[type="submit"]')
                ->logout();
        });
    }

    public function testLoginSuccessfulByAdminAccount()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'admin@gmail.com')
                ->type('password', '12345678')
                ->click('button[type=submit]')
                ->assertPathIs('/admin/dashboard')
                ->logout();
        });
    }

    public function testLoginSuccessfulByUserAccount()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'user@gmail.com')
                ->type('password', '12345678')
                ->click('button[type=submit]')
                ->assertPathIs('/')
                ->logout();
        });
    }

    // Login fail khi sai password và email
    public function testLoginFail()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'userxnxx@gmail.com')
                ->type('password', '12345678xnxx')
                ->click('button[type=submit]')
                ->assertSee(__('Input are wrong'))
                ->assertPathIs('/login')
                ->logout();
        });
    }

    // Để trống field "Email"
    public function testLoginFailEmptyEmail()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('password', '12345678')
                ->click('button[type=submit]')
                ->assertSee(__('validation.required', ['attribute' => 'email']))
                ->assertPathIs('/login')
                ->logout();
        });
    }

    // Email có dấu cách
    public function testLoginSuccessfulEmailWithSpace()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', '     user@gmail.com    ')
                ->type('password', '12345678')
                ->click('button[type=submit]')
                ->assertPathIs('/')
                ->logout();
        });
    }

    // Email hợp lệ nhưng không có trong db
    public function testLoginFailDB()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'userxnxx@gmail.com')
                ->type('password', '12345678')
                ->click('button[type=submit]')
                ->assertSee(__('Input are wrong'))
                ->assertPathIs('/login')
                ->logout();
        });
    }

    // Để trống field "Password"
    public function testLoginFailEmptyPassword()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'user@gmail.com')
                ->click('button[type=submit]')
                ->assertSee(__('validation.required', ['attribute' => 'password']))
                ->assertPathIs('/login')
                ->logout();
        });
    }

    // Nhập ký tự đặc biệt vào field "Password"
    public function testLoginFailPasswordWithSpecialChar()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'user@gmail.com')
                ->type('password', '\'12345678"')
                ->click('button[type=submit]')
                ->assertSee(__('Input are wrong'))
                ->assertPathIs('/login')
                ->logout();
        });
    }

    // Password hợp lệ nhưng không có trong db
    public function testLoginFailPasswordDB()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'user@gmail.com')
                ->type('password', '123456789abc')
                ->click('button[type=submit]')
                ->assertSee(__('Input are wrong'))
                ->assertPathIs('/login')
                ->logout();
        });
    }

    // Để trống tất cả các ô
    public function testLoginFailEmptyAll()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->click('button[type=submit]')
                ->assertSee(__('validation.required', ['attribute' => 'email']))
                ->assertSee(__('validation.required', ['attribute' => 'password']))
                ->assertPathIs('/login')
                ->logout();
        });
    }
}
