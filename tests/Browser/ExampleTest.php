<?php

namespace Tests\Browser;

use App\Models\User;
use Carbon\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Factories\UserFactory; // Import factory UserFactory


class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     */
    public function testUserRegistration()
{
    $this->browse(function ($browser) {
        $browser->visit('/register')
                ->type('name', 'John Doe')
                ->type('email', 'john@example.com')
                ->type('password', 'password')
                ->type('password_confirmation', 'password')
                ->press('Register')
                ->assertPathIs('/home'); // Kiểm tra xem người dùng đã được chuyển hướng đến trang chính sau khi đăng ký
    });
}
public function testUserLoginWithCorrectCredentials()
{
    $this->browse(function ($browser) {
        $browser->visit('/login')
                ->type('email', 'john@example.com')
                ->type('password', 'password')
                ->press('Login')
                ->assertPathIs('/home'); // Kiểm tra xem người dùng đã được chuyển hướng đến trang chính sau khi đăng nhập thành công
    });
}
public function testUserLoginWithIncorrectCredentials()
{
    $this->browse(function ($browser) {
        $browser->visit('/login')
                ->type('email', 'incorrect@example.com')
                ->type('password', 'incorrect_password')
                ->press('Login')
                ->assertPathIs('/login') // Kiểm tra xem người dùng vẫn ở trên trang đăng nhập sau khi nhập sai thông tin đăng nhập
                ->assertSee('These credentials do not match our records.'); // Kiểm tra xem thông báo lỗi hiển thị khi nhập sai thông tin đăng nhập
    });
}

public function testUserLogout()
{
    $user = factory(User::class)->create
    ();

    $this->browse(function ($browser) use ($user) {
        $browser->loginAs($user)
                ->visit('/home')
                ->clickLink('Logout')
                ->assertPathIs('/login'); // Kiểm tra xem người dùng đã được chuyển hướng đến trang đăng nhập sau khi đăng xuất
    });
}
public function testUserRegistrationWithEmailExists()
{
    $existingUser = factory(User::class)->create(['email' => 'john@example.com']);

    $this->browse(function ($browser) {
        $browser->visit('/register')
                ->type('name', 'John Doe')
                ->type('email', 'john@example.com')
                ->type('password', 'password')
                ->type('password_confirmation', 'password')
                ->press('Register')
                ->assertSee('The email has already been taken.'); // Kiểm tra xem thông báo lỗi hiển thị khi đăng ký với email đã tồn tại
    });
}

}
