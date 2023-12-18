<?php

namespace Tests\Unit;

use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_subscription_add(): void
    {
        $token = '7|eRpIBPQoE0KMIkqW6OsdlRUkFKvxx6k6bpsUbqRmfa5d1a94';
        $data = [
            'renewed_at' => date('Y-m-d H:i:s', strtotime("2023-09-09 01:00:00")),
            'expired_at' => date('Y-m-d H:i:s', strtotime("2023-10-09 01:00:00"))
        ];

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post(route('subs.create', ['userid'=>7]), $data)
            ->assertStatus(200)
            ->assertJsonFragment(['success'=>true]);
    }
}
