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

        # possible options:
        # separate the value exporters into a separate abstract type with something like write(value) and canHandle(value, input)
        # the interesting part is that we may have exporters for different options. eg:
        # StringBooleanWriter: this one is the default. writes literal 'true' or 'false'
        # IntBooleanWriter: this one writes 1 or 0 when the idk, --intbool is passed to the command.
        # stderrBooleanWriter: this one exits with 0 when true and 1 when false, when  --errbool is passed to the command. now this one

        if (is_array($value)) {
            # this one possible to be added as an option
            $separator = "\n";
            echo implode($separator, $value);
        } else if (is_bool($value)) {
            echo $value? 'true' : 'false';
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