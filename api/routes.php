<?php

use HomeCEU\DTS\Api;

return [
    new Api\Route('post','/docdata', Api\DocData\DocDataAdd::class),
    new Api\Route('get','/docdata/{docType}/{dataKey}/history', Api\DocData\ListVersions::class)
];