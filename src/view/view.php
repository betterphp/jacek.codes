<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website\view;

abstract class view {

    private $vars = [];

    /**
     * Sets the value of a view var
     *
     * @param string $key A key to identify this variable
     * @param mixed $value Tje value to store (should not be an object)
     *
     * @return void
     */
    public function set_var(string $key, $value): void {
        if (is_object($value)) {
            throw new \Exception('The value of a view variable should not be an object');
        }

        $this->vars[$key] = $value;
    }

    /**
     * Gets the value of a view variable
     *
     * @param string $key The key to return the value of
     *
     * @return mixed The value
     */
    protected function get_var(string $key) {
        return ($this->vars[$key] ?? null);
    }

    /**
     * Renders the output from this view
     *
     * @return void
     */
    abstract public function render(): void;

}
