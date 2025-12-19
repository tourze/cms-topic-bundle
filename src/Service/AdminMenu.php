<?php

declare(strict_types=1);

namespace Tourze\CmsTopicBundle\Service;

use App\Controller\Admin\DashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Knp\Menu\ItemInterface;
use Tourze\CmsTopicBundle\Controller\Admin\TopicCrudController;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;

final readonly class AdminMenu implements MenuProviderInterface
{
    public function __construct(private LinkGeneratorInterface $linkGenerator)
    {
    }

    
    public function __invoke(ItemInterface $item): void
    {
        if (null === $item->getChild('内容中心')) {
            $item->addChild('内容中心')->setExtra('permission', 'CmsBundle');
        }

        $contentCenter = $item->getChild('内容中心');
        if (null !== $contentCenter) {
            $contentCenter->addChild('专题管理')->setUri($this->linkGenerator->getCurdListPage(TopicCrudController::class));
        }
    }
}
