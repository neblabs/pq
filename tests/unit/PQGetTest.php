<?php

namespace Neblabs\PQ\Test\unit;

use Neblabs\PQ\PQGetCommand;
use PHPUnit\Framework\TestCase;

class PQGetTest extends TestCase
{
    public function test_array_assoc()
    {
        $command = new PQGetCommand();
        $file = __DIR__ . '/fixtures/array-assoc.php';

        ob_start();

        $command->get($file, path: 'version');

        $result = ob_get_clean();

        $this->assertSame('1.2.3', $result);
    }

    public function testBooleans()
    {
        $command = new PQGetCommand();
        $file = __DIR__ . '/fixtures/booleans.php';

        ob_start();

        $exitCode = $command->get($file, path: 'enabled');

        $this->assertSame('true', ob_get_clean());
        $this->assertSame(0, $exitCode);

        ob_start();

        $exitCode = $command->get($file, path: 'disabled');

        $this->assertSame('false', ob_get_clean());
        $this->assertSame(0, $exitCode);

        ## here the val doesn't exist, so nothing is printed and 1 is returned

        ob_start();

        $exitCode = $command->get($file, path: 'idontexist');

        $this->assertSame('', ob_get_clean());
        $this->assertSame(1, $exitCode);
    }
}