# CmsTopicBundle

[English](README.md) | [中文](README.zh-CN.md)

一个为 CMS 系统提供专题管理功能的 Symfony 组件包，集成了 EasyAdmin 界面和完整的实体管理功能。

## 功能特性

- **专题管理**：创建、编辑和管理 CMS 专题，包含丰富的元数据
- **EasyAdmin 集成**：无缝的后台管理界面，预配置完整的 CRUD 操作
- **丰富元数据**：支持标题、描述、缩略图、Banner 和推荐标记
- **实体关联**：与 CMS 实体的多对多关系
- **审计追踪**：完整的操作时间戳、用户和 IP 追踪
- **Doctrine 集成**：完整的 ORM 支持和规范的仓储模式

## 系统要求

- PHP 8.1 或更高版本
- Symfony 7.3+
- Doctrine ORM 3.0+
- EasyAdminBundle 4.0+
- CmsBundle（来自同一 monorepo）

## 安装

使用 Composer 安装组件包：

```bash
composer require tourze/cms-topic-bundle
```

## 配置

### 1. 启用组件包

确保在您的 Symfony 应用程序中启用组件包：

```php
// config/bundles.php
return [
    // ...
    Tourze\CmsTopicBundle\CmsTopicBundle::class => ['all' => true],
];
```

### 2. 数据库模式

更新数据库模式以创建必要的表：

```bash
php bin/console doctrine:schema:update --force
```

### 3. 加载示例数据（可选）

加载示例专题数据：

```bash
php bin/console doctrine:fixtures:load --group=topic
```

## 使用方法

### 基础专题实体

`Topic` 实体提供以下字段：

- `id`：自增主键
- `title`：专题标题（必填，最多120字符，唯一）
- `description`：可选的专题描述
- `thumb`：可选的缩略图路径
- `banners`：Banner 配置的 JSON 数组
- `recommend`：推荐/精选专题的布尔标记
- `entities`：与 CMS 实体的多对多关系

### 使用示例

```php
<?php

use Tourze\CmsTopicBundle\Entity\Topic;

// 创建新专题
$topic = new Topic();
$topic->setTitle('技术趋势');
$topic->setDescription('技术发展的最新动态');
$topic->setRecommend(true);

// 设置 Banner 配置
$topic->setBanners([
    [
        'image' => '/path/to/banner.jpg',
        'title' => '2024技术趋势',
        'link' => '/tech-trends'
    ]
]);

// 保存到数据库
$entityManager->persist($topic);
$entityManager->flush();

// 获取专题实体数量
$count = $topic->getEntityCount();
```

### 仓储使用

```php
<?php

use Tourze\CmsTopicBundle\Repository\TopicRepository;

/** @var TopicRepository $topicRepository */
$topicRepository = $entityManager->getRepository(Topic::class);

// 查找推荐专题
$recommendedTopics = $topicRepository->findBy(['recommend' => true]);

// 按标题搜索专题
$searchResults = $topicRepository->findByTitleContaining('技术');
```

### 后台管理界面

组件包提供完整的 EasyAdmin 界面，访问地址：
`/admin/cms-topic/topic`

功能包括：
- 搜索和过滤功能
- 缩略图文件上传
- Banner 配置的 JSON 编辑器
- 实体关联管理
- 审计追踪查看

## 文件结构

```
src/
├── Entity/
│   └── Topic.php              # 主要专题实体
├── Repository/
│   └── TopicRepository.php    # 自定义仓储方法
├── Controller/
│   └── Admin/
│       └── TopicCrudController.php  # EasyAdmin CRUD 控制器
├── Service/
│   └── AdminMenu.php         # 后台菜单集成
├── DataFixtures/
│   └── TopicFixtures.php     # 示例数据
├── DependencyInjection/
│   └── CmsTopicExtension.php # 依赖注入配置
└── Resources/
    └── config/
        ├── services.yaml      # 服务定义
        ├── services_dev.yaml  # 开发环境服务
        └── services_test.yaml # 测试环境服务
```

## 配置选项

### 自定义上传路径

您可以通过覆盖 EasyAdmin 配置来自定义缩略图的上传路径：

```yaml
# config/packages/easy_admin.yaml
easy_admin:
    entities:
        Topic:
            fields:
                - { property: 'thumb', base_path: '/custom/topics', upload_dir: 'public/custom/topics' }
```

### 自定义服务

组件包提供以下可以覆盖的服务：

- `Tourze\CmsTopicBundle\Service\AdminMenu`：后台菜单集成
- `Tourze\CmsTopicBundle\Repository\TopicRepository`：自定义仓储方法

## 测试

运行测试套件：

```bash
php bin/console phpunit tests/
```

运行 PHPStan 分析：

```bash
php bin/console phpstan analyse src/ --level=8
```

## 贡献

1. Fork 仓库
2. 创建功能分支
3. 进行更改
4. 为新功能添加测试
5. 运行测试套件和 PHPStan
6. 提交 Pull Request

## 许可证

此组件包基于 MIT 许可证发布。详细信息请参阅 [LICENSE](LICENSE) 文件。

## 依赖关系

此组件包具有以下主要依赖：

- `doctrine/orm`：数据库操作
- `easycorp/easyadmin-bundle`：后台管理界面
- `symfony/framework-bundle`：核心 Symfony 框架
- `tourze/bundle-dependency`：组件包依赖管理
- `tourze/doctrine-*` 包：增强的 Doctrine 功能
- `tourze/easy-admin-menu-bundle`：后台菜单集成

## 支持

如需报告错误或提出功能请求，请使用问题跟踪器。

## 更新日志

请参阅 [CHANGELOG.md](CHANGELOG.md) 了解版本历史和更改。
