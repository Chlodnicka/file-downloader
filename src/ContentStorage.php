<?php

namespace FileDownloader;

use FileDownloader\ValueObject\Connector;
use FileDownloader\ValueObject\Content;

interface ContentStorage
{
    public function save(Connector $connector, Content $content): void;

    public function remove(Connector $connector): void;
}
