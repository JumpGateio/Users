<?php

namespace Tests;

use Illuminate\View\View;
use JumpGate\Users\Http\Controllers\Authentication;
use JumpGate\Users\Http\Requests\Login;

class AuthenticationTest extends TestCase
{
    /** @test */
    public function it_displays_the_login_form()
    {
        $result = app(Authentication::class)->index();

        $this->assertInstanceOf(View::class, $result);
        $this->assertEquals('auth.login', $result->getName());
    }

    /** @test */
    public function it_errors_when_an_incomplete_form_is_passed()
    {
        $result = app(Authentication::class)->handle(new Login);

        $this->assertInstanceOf(View::class, $result);
        $this->assertEquals('auth.login', $result->getName());
    }
}
