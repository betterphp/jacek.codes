<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website\command;

abstract class command {

    /**
     * Gets a list of command class names, excluding the namespace
     *
     * @return string[] The list of class names
     */
    public static function get_class_names(): array {
        // Ger a list of script files that should define commands
        $script_files = array_filter(scandir(__DIR__), function (string $file): bool {
            return !in_array($file, [
                '.',
                '..',
                basename(__FILE__),
            ]);
        });

        // Work out the class names that those files should define
        $class_names = array_map(function (string $file_name): string {
            return substr($file_name, 0, strpos($file_name, '.'));
        }, $script_files);

        // Return the list zero indexed
        return array_values($class_names);
    }

    /**
     * Gets a command instance by name
     *
     * @param string $command_name The name of the command
     *
     * @return self The instance
     */
    public static function get_by_name(string $command_name): self {
        if (!in_array($command_name, self::get_class_names())) {
            throw new \InvalidArgumentException('Undefined command');
        }

        $full_class_name = __NAMESPACE__ . '\\' . $command_name;

        return new $full_class_name();
    }

    /**
     * Executes the command
     *
     * @return void
     */
    abstract public function execute(): void;

}
