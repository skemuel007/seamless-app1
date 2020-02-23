<?php

namespace Tests;

use Faker\Factory;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $faker;

    public function setUp(): void
    {
        parent::setUp();
        // Artisan::call('db:seed');
        // Artisan::call('migrate:fresh --env=testing');
        $this->faker = Factory::create();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
}
