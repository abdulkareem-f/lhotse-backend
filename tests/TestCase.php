<?php

namespace Tests;

use App\Models\User;
use Exception;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected Generator $faker;
    protected User $user;

    public function setUp(): void {
        parent::setUp();

        $this->faker = Factory::create();
        $this->user = User::create([
            'name'              =>  'Test',
            'email'             =>  'test@test.com',
            'password'          =>  'test_password',
        ]);
    }

    public function __get($key) {
        if ($key === 'faker')
            return $this->faker;
        throw new Exception('Unknown Key Requested');
    }
}
