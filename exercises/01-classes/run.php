<?php

declare(strict_types=1);

require __DIR__ . '/BlogPost.php';

// TODO: instantiate a BlogPost, print it, publish, print again,
//       then try to mutate a readonly property inside try/catch.
//
// Expected output shape (your wording can vary):
//   "Hello world" by raihank43 (unpublished)
//   "Hello world" by raihank43 (published)
//   Cannot modify readonly property BlogPost::$title


$BlogPost = new BlogPost("Hello World", "Description", "Author_Name");
echo $BlogPost->__toString(), PHP_EOL;
$BlogPost->publish();
echo $BlogPost . PHP_EOL;
try {
    $BlogPost->title = 'new';
} catch (\Error $e) {
    echo $e->getMessage();
}
