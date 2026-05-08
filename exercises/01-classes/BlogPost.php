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

class BlogPost
{
    private readonly int $createdAt;
    private ?int $publishedAt = null;

    public function __construct(
        public readonly string $title,
        public readonly string $body,
        public readonly string $author,

    ) {
        $this->createdAt = time();
    }

    public function publish(): void
    {
        if (!$this->publishedAt) {
            $this->publishedAt = time();
        }
    }

    public function isPublished(): bool
    {
        return $this->publishedAt !== null;
    }

    public function summary(int $maxChars = 80): string
    {
        if (mb_strlen($this->body) > $maxChars) {
            return mb_substr($this->body, 0, $maxChars) . "...";
        } else {
            return $this->body;
        }
    }

    public function __toString(): string
    {
        $title = $this->title;
        $isPublished = $this->isPublished() ? "published" : "unpublished";
        $authorName = $this->author;
        return "\"{$title}\" by {$authorName} ({$isPublished})";
    }
}
