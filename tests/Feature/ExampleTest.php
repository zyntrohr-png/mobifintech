<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_home_page_displays_an_environment_notice(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSeeText('environment');
    }
}
