# pq

`pq` is a nice & mighty command-line tool for querying an associative PHP array returned by a file. It uses dotted paths to read nested values.

It currently only reads values from an associative array.

## Installation

### Requirements
- PHP 8.4+
- Bash (to run the installer only)

Run the installer:
```bash
curl -L https://raw.githubusercontent.com/neblabs/pq/main/bin/install.sh | bash
```

This will put it in ~/.local/bin so make sure you have that in your path.
## Usage

```console
pq get <file> <path>
```

### Arguments and options

- `<file>` — required PHP file that returns an array.
- `<path>` — dotted path to the value to print. Examples: `version`, `user.name`, or `user.profile.preferences.theme`.

## File
The input file must be a valid PHP file and must return an array. Example:

```php
<?php

return [
    'version' => '1.2.3',
    'user' => [
        'name' => 'Rafa',
        'profile' => [
            'preferences' => [
                'theme' => 'dark',
            ],
        ],
    ],
    'advanced' => [
        'permissions' => ['read', 'write'],
    ],
];
```

> [!NOTE]
> The file is not parsed but evaluated using `require` so only run this on trusted files.
## Examples

Read a top-level value:

```console
$ pq get config.php version
1.2.3
```

Read a nested value:

```console
$ pq get config.php user.profile.preferences.theme
dark
```

Read an indexed array. Values are separated by a new line.

```console
$ pq get config.php advanced.permissions
read
write
```

## Exit statuses

- `0` — the requested path was found and its value was printed.
- `1` — the requested path was not found in the array. No value is printed. Actually symfony console seems to write a new line char. This may change in the future.
- `1` - Invalid or missing files, invalid PHP syntax, and files that do not return an array result in an error.

## Development

Install the dependencies and run the unit and end-to-end tests with:

```console
composer install
composer test
```

## License

MIT
