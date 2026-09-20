<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // No frontend build (public/build/manifest.json) exists in this
        // environment — only the unused Breeze scaffold views reference
        // @vite(...); the actual admin/public UI doesn't use Vite at all.
        $this->withoutVite();
    }
}
