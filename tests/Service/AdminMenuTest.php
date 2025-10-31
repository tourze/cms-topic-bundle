<?php

declare(strict_types=1);

namespace Tourze\CmsTopicBundle\Tests\Service;

use Knp\Menu\ItemInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\CmsTopicBundle\Service\AdminMenu;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminMenuTestCase;

/**
 * @internal
 */
#[CoversClass(AdminMenu::class)]
#[RunTestsInSeparateProcesses]
final class AdminMenuTest extends AbstractEasyAdminMenuTestCase
{
    private AdminMenu $adminMenu;

    private LinkGeneratorInterface $linkGenerator;

    protected function onSetUp(): void
    {
        $this->linkGenerator = new class implements LinkGeneratorInterface {
            public function getCurdListPage(string $class): string
            {
                return '/admin/topic';
            }

            public function extractEntityFqcn(string $url): ?string
            {
                return null;
            }

            public function setDashboard(string $dashboardControllerFqcn): void
            {
                // No-op for test
            }
        };
        self::getContainer()->set(LinkGeneratorInterface::class, $this->linkGenerator);
        $this->adminMenu = self::getService(AdminMenu::class);
    }

    private function createAdminMenu(): AdminMenu
    {
        return $this->adminMenu;
    }

    public function testAdminMenuImplementsInterface(): void
    {
        $adminMenu = $this->createAdminMenu();
        $reflection = new \ReflectionClass($adminMenu);

        $this->assertTrue($reflection->implementsInterface('Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface'));
    }

    public function testAdminMenuConstructor(): void
    {
        $adminMenu = $this->createAdminMenu();
        $this->assertInstanceOf(AdminMenu::class, $adminMenu);
    }

    public function testInvokeWithExistingContentCenter(): void
    {
        // 从容器获取 AdminMenu 服务实例
        $adminMenu = self::getService(AdminMenu::class);

        $contentCenter = $this->createMock(ItemInterface::class);
        $contentCenter->method('getChild')->with('内容中心')->willReturn($contentCenter);
        $contentCenter->expects($this->once())
            ->method('addChild')
            ->with('专题管理')
            ->willReturn($this->createMock(ItemInterface::class))
        ;

        $rootItem = $this->createMock(ItemInterface::class);
        $rootItem->method('getChild')->with('内容中心')->willReturn($contentCenter);

        $adminMenu->__invoke($rootItem);
    }

    public function testInvokeWithoutContentCenter(): void
    {
        $adminMenu = $this->createAdminMenu();

        $rootItem = $this->createMock(ItemInterface::class);
        $rootItem->method('getChild')->with('内容中心')->willReturn(null);

        $newContentCenter = $this->createMock(ItemInterface::class);
        $rootItem->expects($this->once())
            ->method('addChild')
            ->with('内容中心')
            ->willReturn($newContentCenter)
        ;

        $adminMenu->__invoke($rootItem);
    }
}
