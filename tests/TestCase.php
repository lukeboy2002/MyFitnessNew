<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     */
    public function createApplication()
    {
        $app = parent::createApplication();

        // Enforce SQLite in-memory testing database
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite.database', ':memory:');

        // Hard safeguard against accidentally running tests on development/production database
        $defaultConnection = $app['config']->get('database.default');
        $currentDatabase = $app['config']->get("database.connections.{$defaultConnection}.database");

        if ($defaultConnection === 'mysql' || $currentDatabase === 'myfitnessNew') {
            throw new RuntimeException(
                "Tests are attempting to run against the MySQL development database '{$currentDatabase}'. Execution blocked to protect your data."
            );
        }

        return $app;
    }
}
