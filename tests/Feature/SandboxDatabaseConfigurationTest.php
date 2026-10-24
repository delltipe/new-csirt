<?php

namespace Tests\Feature;

use Database\Seeders\PublicPreviewSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SandboxDatabaseConfigurationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PublicPreviewSeeder::class);
    }

    /**
     * Verify that SQLite remains the configured default database connection.
     */
    public function test_sqlite_remains_the_default_database_connection(): void
    {
        $this->assertSame('sqlite', config('database.default'));
        $this->assertSame('sqlite', DB::getDefaultConnection());
    }

    /**
     * Verify that the default SQLite database path is completely unchanged.
     */
    public function test_default_sqlite_database_path_is_unchanged(): void
    {
        $sqliteConfig = config('database.connections.sqlite');

        $this->assertIsArray($sqliteConfig);
        $this->assertSame('sqlite', $sqliteConfig['driver']);

        // In the PHPUnit test suite, phpunit.xml configures DB_DATABASE=:memory: for fast execution.
        // Verify that the configuration file fallback specifies the standard database.sqlite path.
        $originalEnv = getenv('DB_DATABASE');
        try {
            putenv('DB_DATABASE');
            unset($_ENV['DB_DATABASE'], $_SERVER['DB_DATABASE']);
            $evaluatedConfig = (require config_path('database.php'))['connections']['sqlite'];
            $this->assertSame(database_path('database.sqlite'), $evaluatedConfig['database']);
        } finally {
            if ($originalEnv !== false) {
                putenv("DB_DATABASE={$originalEnv}");
                $_ENV['DB_DATABASE'] = $originalEnv;
                $_SERVER['DB_DATABASE'] = $originalEnv;
            }
        }
    }

    /**
     * Verify that a separate sandbox connection is configured for PostgreSQL.
     */
    public function test_separate_sandbox_connection_is_configured_for_postgresql(): void
    {
        $sandboxConfig = config('database.connections.sandbox');

        $this->assertIsArray($sandboxConfig, 'The [sandbox] database connection must be defined in config/database.php.');
        $this->assertArrayHasKey('url', $sandboxConfig, 'The [sandbox] connection must support the url configuration parameter.');

        // In phpunit.xml, SANDBOX_DB_DRIVER is set to sqlite for safe in-memory test isolation.
        // Verify that the configuration file fallback specifies pgsql as default.
        $originalEnv = getenv('SANDBOX_DB_DRIVER');
        try {
            putenv('SANDBOX_DB_DRIVER');
            unset($_ENV['SANDBOX_DB_DRIVER'], $_SERVER['SANDBOX_DB_DRIVER']);
            $evaluatedConfig = (require config_path('database.php'))['connections']['sandbox'];
            $this->assertSame('pgsql', $evaluatedConfig['driver'], 'The [sandbox] default driver must be pgsql.');
        } finally {
            if ($originalEnv !== false) {
                putenv("SANDBOX_DB_DRIVER={$originalEnv}");
                $_ENV['SANDBOX_DB_DRIVER'] = $originalEnv;
                $_SERVER['SANDBOX_DB_DRIVER'] = $originalEnv;
            }
        }
    }

    /**
     * Verify that the sandbox connection can receive DATABASE_URL without changing the default connection.
     */
    public function test_sandbox_connection_receives_database_url_without_changing_default_connection(): void
    {
        $simulatedDatabaseUrl = 'postgres://testuser:testpass@testhost:5432/testdb?sslmode=require';

        Config::set('database.connections.sandbox.url', $simulatedDatabaseUrl);

        $sandboxConfig = config('database.connections.sandbox');
        $this->assertSame($simulatedDatabaseUrl, $sandboxConfig['url']);

        // Default connection must remain strictly sqlite
        $this->assertSame('sqlite', config('database.default'));
        $this->assertSame('sqlite', DB::getDefaultConnection());
        $this->assertSame('sqlite', config('database.connections.sqlite.driver'));
    }

    /**
     * Verify that no sandbox connection attempt occurs merely because the application boots.
     */
    public function test_no_sandbox_connection_attempt_occurs_merely_because_the_application_boots(): void
    {
        // Set an unreachable/fictional database URL for sandbox
        Config::set('database.connections.sandbox.url', 'postgres://unreachable.invalid:5432/nonexistent');

        // Accessing public showcase route boots the application and performs database queries against SQLite
        $response = $this->get('/');
        $response->assertOk();

        // Verify that only the default connection is in the active PDO connections pool
        $activeConnections = DB::getConnections();
        $this->assertArrayNotHasKey('sandbox', $activeConnections, 'The [sandbox] connection must not be established on application boot.');
    }
}
