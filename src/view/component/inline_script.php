<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website\view\component;

class inline_script extends component implements script {

    private $code;

    /**
     * @param string $code The JavaScript code
     */
    public function __construct(string $code) {
        $this->code = $code;
    }

    /**
     * @inheritDoc
     */
    public function render(): void {
        ?>
        <script type="text/javascript">
            <?= $this->code ?>
        </script>
        <?php
    }

}
