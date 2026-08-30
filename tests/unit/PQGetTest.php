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
}