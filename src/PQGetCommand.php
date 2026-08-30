<?php

namespace Neblabs\PQ;

use Adbar\Dot;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;

class PQGetCommand extends Command
{
    /**
     * Laughably simple, for now let's not over-engineer it so let's keep the logic here.
     * @throws \Exception
     */
    #[AsCommand('get')]
    public function get(
        #[Argument('The file that returns the php array.')] string $file,
        #[Argument('The dotted path key to the item. examples: version, user.name')] string $path
    ): int
    {
        $data = new Dot($this->readArray($file));

        if (!$data->has($path)) {
            return 1;
        }

        $value = $data->get($path);

        # this one possible to be added as an option
        $separator = "\n";

        if (is_array($value)) {
            echo implode($separator, $value);
        } else {
            echo $value;
        }

        return 0;
    }

    private function readArray(string $file) : array
    {
        # first let's validate the file.
        if (!file_exists($file)) {
            throw new \Exception("$file: file does not exist");
        } else if (exec("php -l $file", $output, $res) === false || $res !== 0) {
            throw new \Exception("$file: file is not valid php. log:\n". implode("\n", $output));
        }

        $data = require "$file"; # not technically required but a wink to posix shell scripting :)

        if (!is_array($data)) {
            throw new \Exception("$file: file does not return a valid array.");
        }

        return $data;
    }
}