<?php

namespace Neblabs\Tests\e2e;

use Composer\InstalledVersions;
use PHPUnit\Framework\TestCase;

class PQGete2ETest extends TestCase
{
    private static string $EXECUTABLE;

    public static function setupBeforeClass(): void
    {
        static::$EXECUTABLE = InstalledVersions::getInstallPath('neblabs/pq') . '/bin/pq';

        chmod(static::$EXECUTABLE, 0774);
    }

    public function test_reads_assoc()
    {
        $file = __DIR__ . '/fixtures/array-assoc.php';

        $this->assertSame(
            '1.2.3',
            trim(shell_exec(static::$EXECUTABLE . " get $file version"))
        );

        # lvl 2
        $this->assertSame(
            'Rafa',
            trim(shell_exec(static::$EXECUTABLE . " get $file user.name"))
        );

        # lvl 4
        $this->assertSame(
            'dark',
            trim(shell_exec(static::$EXECUTABLE . " get $file user.profile.preferences.theme"))
        );

        # returns indexed array values separated by a new line. possibly to configure this in the future as an option
        $this->assertSame(
            "read\nwrite",
            trim(shell_exec(static::$EXECUTABLE . " get $file advanced.permissions"))
        );
    }

    public function test_reads_assoc_no_key_exits_with_1_and_no_output()
    {
        $file = __DIR__ . '/fixtures/array-assoc.php';

        exec(static::$EXECUTABLE . " get $file idontexist", $output, $res);

        $this->assertSame(1, $res);
        $this->assertEquals([''], $output); # prints a new line, probably a todo for later to figure this out
    }
}