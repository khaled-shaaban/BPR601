<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Seed the database (roles/permissions, governorates, areas, admin user)
     * before each test so feature tests run against realistic reference data.
     */
    protected bool $seed = true;
}
