<?php

declare(strict_types=1);

namespace betterphp\jacek_codes_website\view\component;

class stylesheet extends component {

    private $href;
    private $media;

    /**
     * @param string $href The URL to find the stylesheet at
     * @param string $media The value of the media attribute
     */
    public function __construct(string $href, string $media) {
        $this->href = $href;
        $this->media = $media;
    }

    /**
     * @inheritDoc
     */
    public function render(): void {
        ?>
        <link rel="stylesheet" type="text/css" media="<?= $this->media; ?>" href="<?= $this->href; ?>" />
        <?php
    }

}
