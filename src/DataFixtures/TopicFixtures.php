<?php

declare(strict_types=1);

namespace Tourze\CmsTopicBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use Tourze\CmsTopicBundle\Entity\Topic;

#[When(env: 'test')]
#[When(env: 'dev')]
final class TopicFixtures extends Fixture
{
    public const TOPIC_SPORTS_REFERENCE = 'topic-sports';

    public function load(ObjectManager $manager): void
    {
        $topic = new Topic();
        $topic->setTitle('运动专题');
        $topic->setDescription('这是一个关于运动相关的话题');
        $topic->setRecommend(true);

        $manager->persist($topic);
        $this->addReference(self::TOPIC_SPORTS_REFERENCE, $topic);

        $manager->flush();
    }
}
