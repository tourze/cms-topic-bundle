<?php

declare(strict_types=1);

namespace Tourze\CmsTopicBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\CmsTopicBundle\Entity\Topic;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;

/**
 * @internal
 */
#[CoversClass(Topic::class)]
final class TopicTest extends AbstractEntityTestCase
{
    protected function createEntity(): Topic
    {
        return new Topic();
    }

    public function testTopicEntity(): void
    {
        $topic = $this->createEntity();

        $this->assertSame(0, $topic->getId());
        $this->assertNull($topic->getTitle());
        $this->assertNull($topic->getDescription());
        $this->assertNull($topic->getThumb());
        $this->assertSame([], $topic->getBanners());
        $this->assertNull($topic->getRecommend());
        $this->assertSame(0, $topic->getEntityCount());
    }

    public function testSetTitle(): void
    {
        $topic = $this->createEntity();
        $topic->setTitle('Test Topic');

        $this->assertSame('Test Topic', $topic->getTitle());
        // __toString returns empty string for new entities (id = 0)
        $this->assertSame('', (string) $topic);
    }

    public function testSetDescription(): void
    {
        $topic = $this->createEntity();
        $topic->setDescription('Test Description');

        $this->assertSame('Test Description', $topic->getDescription());
    }

    public function testSetThumb(): void
    {
        $topic = $this->createEntity();
        $topic->setThumb('test.jpg');

        $this->assertSame('test.jpg', $topic->getThumb());
    }

    public function testSetBanners(): void
    {
        $topic = $this->createEntity();
        $banners = [
            'banner1' => ['image' => 'banner1.jpg', 'url' => 'https://example.com'],
            'banner2' => ['image' => 'banner2.jpg', 'url' => 'https://example2.com'],
        ];

        $topic->setBanners($banners);
        $this->assertSame($banners, $topic->getBanners());
    }

    public function testSetBannersWithNull(): void
    {
        $topic = $this->createEntity();
        $topic->setBanners(null);

        $this->assertSame([], $topic->getBanners());
    }

    public function testSetRecommend(): void
    {
        $topic = $this->createEntity();
        $topic->setRecommend(true);

        $this->assertTrue($topic->getRecommend());
    }

    public function testToStringWithEmptyTopic(): void
    {
        $topic = $this->createEntity();

        $this->assertSame('', (string) $topic);
    }

    public function testFluentSetters(): void
    {
        $topic = $this->createEntity();

        $topic->setTitle('Test');
        $this->assertSame('Test', $topic->getTitle());

        $topic->setDescription('Description');
        $this->assertSame('Description', $topic->getDescription());

        $topic->setThumb('thumb.jpg');
        $this->assertSame('thumb.jpg', $topic->getThumb());

        $topic->setBanners(['test' => 'data']);
        $this->assertSame(['test' => 'data'], $topic->getBanners());

        $topic->setRecommend(true);
        $this->assertTrue($topic->getRecommend());
    }

    /**
     * 提供属性及其样本值的 Data Provider.
     *
     * @return iterable<array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        yield 'title' => ['title', 'Test Topic Title'];
        yield 'description' => ['description', 'Test topic description with detailed information'];
        yield 'thumb' => ['thumb', 'topic-thumbnail.jpg'];
        yield 'banners' => ['banners', [
            'banner1' => ['image' => 'banner1.jpg', 'url' => 'https://example.com/banner1'],
            'banner2' => ['image' => 'banner2.jpg', 'url' => 'https://example.com/banner2'],
        ]];
        yield 'recommend' => ['recommend', true];
    }
}
