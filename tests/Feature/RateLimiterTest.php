<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RateLimiterTest extends TestCase
{

    /**
     * Test rate limit by exceeding rate limit.
     *
     * @return void
     */
    public function test_rate_limit_exceed_limit(): void
    {
        for ($i = 0; $i < 100; $i++) {
            $this->getJson('/api/v1/tasks');
        }

        $response = $this->getJson('/api/v1/tasks');
        $response->assertStatus(429);
    }

    /**
     * Test if withing the rate limit.
     *
     * @return void
     */
    public function test_rate_limit_within_limit(): void
    {
        for ($i = 0; $i < 100; $i++) {
            $response = $this->getJson('/api/v1/tasks');
            $response->assertStatus(200);
        }
    }

    /**
     * Test if rate limit get reset.
     *
     * @return void
     */
    public function test_rate_limit_rest(): void
    {
        for ($i = 0; $i < 100; $i++) {
            $this->getJson('/api/v1/tasks');
        }

        $response = $this->getJson('/api/v1/tasks');
        $response->assertStatus(429);

        $this->travel(61)->seconds();// to wait

        $response = $this->getJson('/api/v1/tasks');
        $response->assertSuccessful();
    }
}
