<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegisterTest extends DuskTestCase
{
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

    public function testRegisterScreenCanBeRendered()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->assertSee(__('Login'))
                ->assertSee(__('Register'))
                ->assertPresent('input[name="name"]')
                ->assertPresent('input[name="email"]')
                ->assertPresent('input[name="phone"]')
                ->assertPresent('input[name="password"]')
                ->assertPresent('input[name="password_confirmation"]')
                ->assertPresent('button[type="submit"]')
                ->logout();
        });
    }

    // để trống tất cả các trường
    public function testLoginFailEmptyAll()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->click('button[type=submit]')
                ->assertSee(__('validation.required', ['attribute' => 'name']))
                ->assertSee(__('validation.required', ['attribute' => 'email']))
                ->assertSee(__('validation.required', ['attribute' => 'phone']))
                ->assertSee(__('validation.required', ['attribute' => 'password']))
                ->assertPathIs('/register')
                ->pause(1000)
                ->logout();
        });
    }

    // tài khoản đã tồn tại
    public function testRegisterFailExists()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->type('name', 'admin')
                ->type('email', 'admin@gmail.com')
                ->type('phone', '0123456789')
                ->type('password', '12345678')
                ->type('password_confirmation', '12345678')
                ->click('button[type=submit]')
                ->assertSee(__('validation.unique', ['attribute' => 'email']))
                ->assertSee(__('validation.unique', ['attribute' => 'phone']))
                ->assertPathIs('/register')
                ->pause(1000)
                ->logout();
        });
    }

    // test phone không đúng định dạng
    public function testLoginFailPhoneFormat()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->type('name', 'admin')
                ->type('email', 'admin123@gmail.com')
                ->type('phone', '0987')
                ->type('password', '12345678')
                ->type('password_confirmation', '12345678')
                ->click('button[type=submit]')
                ->assertSee(__('validation.regex', ['attribute' => 'phone', 'regex' => '/0[1-9]{9}/']))
                ->assertPathIs('/register')
                ->pause(1000)
                ->logout();
        });
    }

    // độ dài input nhập vào không hợp lệ
    public function testLoginFailLengthInput()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->type('name', 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Aliquid ab tempore exercitati
                    onem tenetur? Similique harum placeat laboriosam eius, culpa odio obcaecati, doloremque quibusdam
                    voluptatibus consequuntur assumenda rerum quis. Animi voluptates laboriosam iusto placeat! Qui
                    inventore rem nesciunt id odio quo reiciendis vitae quaerat in quia itaque eos, accusamus quis
                    praesentium voluptatibus nisi consequuntur alias hic? Harum laudantium quibusdam animi debitis po
                    rro est earum voluptate quaerat aut impedit. Dolores quia modi incidunt id a laborum atque, eveniet
                    , perspiciatis tempore, alias officia tempora neque laboriosam maiores voluptatem rem? Nisi rem cum
                    que officia eligendi unde provident soluta ullam? Id fugit non dolores ducimus totam magni nihil qu
                    idem sint quis aperiam, adipisci odit asperiores numquam tempore animi, sapiente beatae ipsum assum
                    enda! Numquam non, unde error, labore reiciendis ut delectus nobis corporis iusto sed illo ipsam cu
                    piditate illum amet debitis obcaecati ab quos. Ipsa aliquid animi molestias repellendus rem placeat
                    accusantium recusandae quos voluptatem voluptatibus similique labore, perferendis unde omnis dolor
                    doloribus repudiandae perspiciatis aspernatur eveniet voluptate suscipit. Libero laboriosam
                    corporis itaque nisi repellendus nesciunt corrupti maiores vero? Ab, soluta? Fugit aliquid minima
                    voluptatum ut ducimus asperiores similique excepturi ullam quo amet dolore quam odio vel eos eaque
                    , molestias aspernatur non doloribus ratione commodi id esse dignissimos quaerat neque? Incidunt
                    unde dolor nostrum sunt, aperiam nulla inventore dolorum nemo, natus illo ratione dolorem dolore?
                    Ad facere saepe nam quidem, excepturi harum modi aliquam culpa fuga exercitationem? Facilis 
                    numquam ad dicta illo laudantium voluptatum modi, debitis, aliquam maiores voluptatem nobis veniam
                    ab corrupti perferendis quam a nemo. Magni modi vitae laboriosam dolorem sint eius, consequuntur 
                    dolore pariatur tenetur dolor quis recusandae voluptatum commodi dignissimos earum, aliquid accus
                    amus cumque ducimus saepe. Molestiae nulla debitis consectetur fuga. Quos laborum, commodi, neq')
                ->type('email', 'admin12@gmail.com')
                ->type('phone', '0123456781')
                ->type('password', '123')
                ->type('password_confirmation', '123')
                ->click('button[type=submit]')
                ->assertSee(__('validation.max.string', ['attribute' => 'name', 'max' => '255']))
                ->assertSee(__('validation.min.string', ['attribute' => 'password', 'min' => '8']))
                ->assertPathIs('/register')
                ->pause(1000)
                ->logout();
        });
    }

    // fail mật khẩu không khớp
    public function testLoginFailPasswordNotMatch()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->type('name', 'admin')
                ->type('email', 'admin123@gmail.com')
                ->type('phone', '0123456781')
                ->type('password', '12345678')
                ->type('password_confirmation', '1234567890')
                ->click('button[type=submit]')
                ->assertSee(__('validation.confirmed', ['attribute' => 'password']))
                ->assertPathIs('/register')
                ->pause(1000)
                ->logout();
        });
    }

    // test đăng ký thành công
    public function testRegisterSuccessful()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->type('name', 'admin123')
                ->type('email', 'admin123@gmail.com')
                ->type('phone', '0987654321')
                ->type('password', '12345678')
                ->type('password_confirmation', '12345678')
                ->click('button[type=submit]')
                ->assertPathIs('/login')
                ->assertSee(__('Registed successfully'))
                ->pause(1000)
                ->logout();
        });
    }
}
