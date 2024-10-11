<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Tag;
use PHPUnit\Framework\TestCase;

class TagTest extends TestCase
{
    public function testConstruct(): void
    {
        $currentDate = new \DateTimeImmutable();
        $tag = new Tag($currentDate, $currentDate);

        self::assertEquals($currentDate, $tag->getCreatedAt());
        self::assertEquals($currentDate, $tag->getUpdatedAt());
    }
}
