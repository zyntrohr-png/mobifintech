<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_home_page_displays_the_application_and_environment(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Your app is up and running.')
            ->assertSee('Testing environment')
            ->assertSee('Laravel ' . \Illuminate\Foundation\Application::VERSION);
    }
}
