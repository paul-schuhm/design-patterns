<?php

use PHPUnit\Framework\TestCase;
use Ps\App\Models\Post;

final class PostTest extends TestCase
{
    protected function setUp(): void
    {
        // Réinitialiser $last_id avant chaque test
        $reflection = new \ReflectionProperty(Post::class, 'last_id');
        $reflection->setAccessible(true);
        $reflection->setValue(null, 1);
    }

    public function testAutomaticIdIncrement(): void
    {
        $post1 = new Post(slug: 'first', title: 'First Post', date_publication: new \DateTimeImmutable(), author: 1);
        $post2 = new Post(slug: 'second', title: 'Second Post', date_publication: new \DateTimeImmutable(), author: 2);

        $this->assertSame(1, $post1->id);
        $this->assertSame(2, $post2->id);
    }
}
