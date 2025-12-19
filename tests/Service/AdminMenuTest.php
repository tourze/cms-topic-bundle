<?php

declare(strict_types=1);

namespace Tourze\CmsTopicBundle\Tests\Service;

use Knp\Menu\MenuFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\CmsTopicBundle\Service\AdminMenu;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminMenuTestCase;

/**
 * AdminMenu 集成测试.
 *
 * @internal
 */
#[CoversClass(AdminMenu::class)]
#[RunTestsInSeparateProcesses]
final class AdminMenuTest extends AbstractEasyAdminMenuTestCase
{
    private AdminMenu $adminMenu;

    protected function onSetUp(): void
    {
        $this->adminMenu = self::getService(AdminMenu::class);
    }

    public function testServiceIsAvailableInContainer(): void
    {
        $adminMenu = self::getService(AdminMenu::class);
        $this->assertInstanceOf(AdminMenu::class, $adminMenu);
        $this->assertInstanceOf(MenuProviderInterface::class, $adminMenu);
    }

    public function testInvokeAddsContentCenterMenuWhenNotExists(): void
    {
        $factory = new MenuFactory();
        $rootMenu = $factory->createItem('root');

        ($this->adminMenu)($rootMenu);

        $this->assertTrue($rootMenu->hasChildren());
        $contentCenterMenu = $rootMenu->getChild('内容中心');
        $this->assertNotNull($contentCenterMenu);
        $this->assertEquals('CmsBundle', $contentCenterMenu->getExtra('permission'));
    }

    public function testInvokeAddsTopicManagementMenuItem(): void
    {
        $factory = new MenuFactory();
        $rootMenu = $factory->createItem('root');

        ($this->adminMenu)($rootMenu);

        $contentCenterMenu = $rootMenu->getChild('内容中心');
        $this->assertNotNull($contentCenterMenu);

        $topicMenuItem = $contentCenterMenu->getChild('专题管理');
        $this->assertNotNull($topicMenuItem);
        $this->assertNotNull($topicMenuItem->getUri());
    }

    public function testInvokeUsesExistingContentCenterMenu(): void
    {
        $factory = new MenuFactory();
        $rootMenu = $factory->createItem('root');
        $existingContentCenter = $rootMenu->addChild('内容中心');

        ($this->adminMenu)($rootMenu);

        $contentCenterMenu = $rootMenu->getChild('内容中心');
        $this->assertSame($existingContentCenter, $contentCenterMenu);
        $this->assertNotNull($contentCenterMenu->getChild('专题管理'));
    }
}
