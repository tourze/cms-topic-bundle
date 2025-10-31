<?php

declare(strict_types=1);

namespace Tourze\CmsTopicBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use Tourze\CmsTopicBundle\Entity\Topic;

/**
 * @extends AbstractCrudController<Topic>
 */
#[AdminCrud(
    routePath: '/cms-topic/topic',
    routeName: 'cms_topic_topic'
)]
final class TopicCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Topic::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('专题')
            ->setEntityLabelInPlural('专题管理')
            ->setPageTitle(Crud::PAGE_INDEX, '专题列表')
            ->setPageTitle(Crud::PAGE_NEW, '新建专题')
            ->setPageTitle(Crud::PAGE_EDIT, '编辑专题')
            ->setPageTitle(Crud::PAGE_DETAIL, '专题详情')
            ->setDefaultSort(['createTime' => 'DESC'])
            ->setSearchFields(['title', 'description'])
            ->showEntityActionsInlined()
            ->setFormThemes(['@EasyAdmin/crud/form_theme.html.twig'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')
            ->onlyOnIndex()
        ;

        yield TextField::new('title', '标题')
            ->setColumns('col-md-6')
            ->setRequired(true)
            ->setMaxLength(120)
        ;

        yield TextareaField::new('description', '描述')
            ->setColumns('col-md-12')
            ->setMaxLength(65535)
            ->hideOnIndex()
        ;

        yield ImageField::new('thumb', '缩略图')
            ->setBasePath('/uploads/topics')
            ->setUploadDir('public/uploads/topics')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setColumns('col-md-6')
            ->hideOnIndex()
        ;

        yield ArrayField::new('banners', 'Banner配置')
            ->setHelp('JSON格式的Banner配置数组')
            ->hideOnIndex()
            ->onlyOnForms()
        ;

        yield BooleanField::new('recommend', '推荐状态')
            ->renderAsSwitch(false)
        ;

        yield AssociationField::new('entities', '关联内容')
            ->setColumns('col-md-12')
            ->hideOnIndex()
            ->onlyOnForms()
        ;

        yield TextField::new('createdFromIp', '创建IP')
            ->onlyOnDetail()
        ;

        yield TextField::new('updatedFromIp', '更新IP')
            ->onlyOnDetail()
        ;

        yield DateTimeField::new('createTime', '创建时间')
            ->hideOnForm()
            ->setFormat('yyyy-MM-dd HH:mm:ss')
        ;

        yield DateTimeField::new('updateTime', '更新时间')
            ->onlyOnDetail()
            ->setFormat('yyyy-MM-dd HH:mm:ss')
        ;

        yield AssociationField::new('createdBy', '创建者')
            ->onlyOnDetail()
        ;

        yield AssociationField::new('updatedBy', '更新者')
            ->onlyOnDetail()
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('title')
            ->add(BooleanFilter::new('recommend'))
            ->add(DateTimeFilter::new('createTime'))
            ->add(DateTimeFilter::new('updateTime'))
            ->add('createdBy')
            ->add('entities')
        ;
    }
}
