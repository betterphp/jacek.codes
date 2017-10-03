<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website\view\component;

class script extends component {

    private $src;
    private $defer;
    private $async;

    /**
     * @param string $src The URL to find the script at
     * @param boolean $defer If execution should only be started once the DOM is ready
     * @param boolean $async If the script should be downloaded async
     */
    public function __construct(string $src, bool $defer = false, bool $async = false) {
        $this->src = $src;
        $this->defer = $defer;
        $this->async = $async;
    }

    /**
     * @inheritDoc
     */
    public function render(): void {
        ?>
        <script type="text/javascript"
                src="<?= $this->src; ?>"
                <?= ($this->defer) ? 'defer="defer"' : ''; ?>
                <?= ($this->async) ? 'async="async"' : ''; ?>
        >
        </script>
        <?php
    }

}
