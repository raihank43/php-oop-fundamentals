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
