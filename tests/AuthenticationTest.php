<?php

namespace Tests;

class AuthenticationTest extends TestCase
{
    /** @test */
    public function it_displays_the_login_form()
    {
        $result = app(\JumpGate\Users\Http\Controllers\Authentication::class)->index();

        $this->assertInstanceOf(\Illuminate\View\View::class, $result);
        $this->assertEquals('auth.login', $result->getName());
    }
}
