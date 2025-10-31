<?php

declare(strict_types=1);

namespace Tourze\CmsTopicBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\CmsTopicBundle\DependencyInjection\CmsTopicExtension;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;

/**
 * @internal
 */
#[CoversClass(CmsTopicExtension::class)]
class CmsTopicExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
    private CmsTopicExtension $extension;

    private ContainerBuilder $container;

    protected function setUp(): void
    {
        parent::setUp();
        $this->extension = new CmsTopicExtension();
        $this->container = new ContainerBuilder();
        $this->container->setParameter('kernel.environment', 'test');
    }

    public function testLoadWithEmptyConfigs(): void
    {
        $this->extension->load([], $this->container);

        // 验证 AdminMenu 服务已加载
        $this->assertTrue(
            $this->container->hasDefinition('Tourze\CmsTopicBundle\Service\AdminMenu')
            || $this->container->hasAlias('Tourze\CmsTopicBundle\Service\AdminMenu')
        );
    }

    public function testExtensionAlias(): void
    {
        $this->assertEquals('cms_topic', $this->extension->getAlias());
    }
}
