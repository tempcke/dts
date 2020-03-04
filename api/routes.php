<?php

use HomeCEU\DTS\Api;

return [
    new Api\Route('post','/docdata', Api\DocData\DocDataAdd::class),
    new Api\Route('get','/docdata/{docType}/{dataKey}/history', Api\DocData\ListVersions::class),
    new Api\Route('head', '/docdata/{docType}/{dataKey}', Api\DocData\Exists::class),
    new Api\Route('get', '/render/{docType}/{templateKey}/{dataKey}', Api\Render::class)
];