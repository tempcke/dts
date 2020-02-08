<?php

use HomeCEU\DTS\Api;

return [
  new Api\Route('post','/docdata', Api\DocData\DocDataAdd::class)
];