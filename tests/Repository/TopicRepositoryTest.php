<?php

declare(strict_types=1);

namespace Tourze\CmsTopicBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\CmsTopicBundle\Entity\Topic;
use Tourze\CmsTopicBundle\Repository\TopicRepository;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;

/**
 * @internal
 */
#[CoversClass(TopicRepository::class)]
#[RunTestsInSeparateProcesses]
class TopicRepositoryTest extends AbstractRepositoryTestCase
{
    protected function createNewEntity(): Topic
    {
        $topic = new Topic();
        $topic->setTitle('Test Topic ' . uniqid());
        $topic->setDescription('Test Description');

        return $topic;
    }

    protected function getRepository(): TopicRepository
    {
        $repository = self::getContainer()->get(TopicRepository::class);
        self::assertInstanceOf(TopicRepository::class, $repository);

        return $repository;
    }

    protected function onSetUp(): void
    {
        // Setup can be done here if needed
    }

    public function testRemove(): void
    {
        $repository = $this->getRepository();
        $topic = $this->createNewEntity();

        // Use the repository's save method
        $repository->save($topic);
        $topicId = $topic->getId();
        $this->assertGreaterThan(0, $topicId);

        $repository->remove($topic);
        self::getEntityManager()->clear();

        $foundTopic = $repository->find($topicId);
        $this->assertNull($foundTopic);
    }
}
