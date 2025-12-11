<?php

namespace Tests;

use App\Services\Mailtrap\MailtrapService;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Mock Mailtrap service for all tests
        $this->mock(MailtrapService::class, function ($mock) {
            $mock->shouldReceive('sendTemplateEmail')->andReturn(['success' => true]);
            $mock->shouldReceive('sendPaymentSuccess')->andReturn(['success' => true]);
            $mock->shouldReceive('sendOrderConfirmation')->andReturn(['success' => true]);
            $mock->shouldReceive('sendWelcomeEmail')->andReturn(['success' => true]);
        });
    }
}
