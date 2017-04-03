<?php

namespace Tests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use JumpGate\Users\Events\UserFailedLogin;
use JumpGate\Users\Events\UserLoggedIn;
use JumpGate\Users\Models\User;
use JumpGate\Users\Models\User\Status;
use JumpGate\Users\Services\Login;

class LoginServiceTest extends TestCase
{
    /** @test */
    public function it_handles_invalid_credentials()
    {
        // Arrange
        Event::fake();
        Auth::shouldReceive('attempt')->once()->andReturn(false);
        URL::shouldReceive('route')->once()->andReturn('/test');

        // Act
        $result = (new Login)->loginUser(['email' => 'test']);

        // Assert
        Event::assertDispatched(UserFailedLogin::class, function ($event) {
            return $event->reason === 'password';
        });
        $this->assertFalse($result->success);
    }

    /** @test */
    public function it_handles_inactive_users()
    {
        // Arrange
        Event::fake();
        Auth::shouldReceive('attempt')->once()->andReturn(true);
        Auth::shouldReceive('logout')->once()->andReturn(true);
        Auth::shouldReceive('user')->twice()->andReturn((object)[
            'status_id' => Status::INACTIVE,
            'email'     => 'test@test.com',
        ]);
        URL::shouldReceive('route')->once()->andReturn('/test');

        // Act
        $result = (new Login)->loginUser(['email' => 'test']);

        // Assert
        Event::assertDispatched(UserFailedLogin::class, function ($event) {
            return $event->reason === 'inactive';
        });
        $this->assertFalse($result->success);
        $this->assertEquals('test@test.com', session('inactive_email'));
    }

    /** @test */
    public function it_handles_blocked_users()
    {
        // Arrange
        Event::fake();
        Auth::shouldReceive('attempt')->once()->andReturn(true);
        Auth::shouldReceive('logout')->once()->andReturn(true);
        Auth::shouldReceive('user')->twice()->andReturn((object)[
            'status_id' => Status::BLOCKED,
            'email'     => 'test@test.com',
        ]);
        URL::shouldReceive('route')->once()->andReturn('/test');

        // Act
        $result = (new Login)->loginUser(['email' => 'test']);

        // Assert
        Event::assertDispatched(UserFailedLogin::class, function ($event) {
            return $event->reason === 'blocked';
        });
        $this->assertFalse($result->success);
    }

    /** @test */
    public function it_logs_users_in()
    {
        factory(\JumpGate\Users\Models\User::class)->create();

        // Arrange
        Event::fake();
        Auth::shouldReceive('attempt')->once()->andReturn(true);
        Auth::shouldReceive('check')->once()->andReturn(false);
        Auth::shouldReceive('user')->andReturn(User::find(1));
        URL::shouldReceive('route')->once()->andReturn('/test');

        // Act
        $result = (new Login)->loginUser(['email' => 'test']);

        // Assert
        Event::assertDispatched(UserLoggedIn::class);
        $this->assertTrue($result->success);
    }
}
