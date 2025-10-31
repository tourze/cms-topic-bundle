<?php

declare(strict_types=1);

namespace Tourze\CmsTopicBundle\Tests\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

/**
 * 测试用的简单 Dashboard 控制器
 */
class TestDashboardController extends AbstractDashboardController
{
    public function index(): \Symfony\Component\HttpFoundation\Response
    {
        return new \Symfony\Component\HttpFoundation\Response('Test Dashboard');
    }

    public function configureDashboard(): \EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard
    {
        return \EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard::new()
            ->setTitle('Test Dashboard');
    }

    public function configureMenuItems(): iterable
    {
        return [];
    }
}