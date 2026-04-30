<?php
declare(strict_types=1);

// TODO: implement the BlogPost class per exercises/01-classes/README.md
//
// Hints:
//  - constructor property promotion: public function __construct(
//        public readonly string $title, ...
//    ) {}
//  - $createdAt: declare it as a promoted parameter with a default of 0,
//    then overwrite with time() inside the body if it's still 0 — or
//    just take it as a non-promoted private property and assign in the
//    body. Either approach is fine; pick one and explain in STRUGGLES
//    if you're unsure.
//  - mb_substr($body, 0, $maxChars) for the summary truncation.
