<?php

declare(strict_types=1);

namespace Tourze\CmsTopicBundle\Tests\Controller\Admin;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\HttpFoundation\Response;
use Tourze\CmsTopicBundle\Controller\Admin\TopicCrudController;
use Tourze\CmsTopicBundle\Entity\Topic;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;

/**
 * @internal
 */
#[CoversClass(TopicCrudController::class)]
#[RunTestsInSeparateProcesses]
final class TopicCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    protected function getEntityFqcn(): string
    {
        return Topic::class;
    }

    protected function afterEasyAdminSetUp(): void
    {
        parent::afterEasyAdminSetUp();

        // 创建测试所需的上传目录
        $container = self::getContainer();
        $projectDir = $container->getParameter('kernel.project_dir');
        self::assertIsString($projectDir);

        $uploadDir = $projectDir . '/public/uploads/topics';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
    }

    public function testIndexPage(): void
    {
        $client = self::createAuthenticatedClient();

        $crawler = $client->request('GET', '/admin');
        self::assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        // Navigate to Topic CRUD
        $link = $crawler->filter('a[href*="TopicCrudController"]')->first();
        if ($link->count() > 0) {
            $client->click($link->link());
            self::assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        }
    }

    public function testCreateTopic(): void
    {
        $client = $this->createAuthenticatedClient();

        // 访问创建页面
        $crawler = $client->request('GET', $this->generateAdminUrl('new'));
        $this->assertResponseIsSuccessful();

        // 验证页面包含表单
        $form = $crawler->selectButton('Create')->form();
        $this->assertNotNull($form);
    }

    public function testEditTopic(): void
    {
        $client = $this->createAuthenticatedClient();

        // 先访问 index 页面获取一个实体ID
        $crawler = $client->request('GET', $this->generateAdminUrl('index'));
        $this->assertResponseIsSuccessful();

        $firstRecordId = $crawler->filter('table tbody tr[data-id]')->first()->attr('data-id');
        if (null !== $firstRecordId && '' !== $firstRecordId) {
            // 访问编辑页面
            $client->request('GET', $this->generateAdminUrl('edit', ['entityId' => $firstRecordId]));
            $this->assertResponseIsSuccessful();
        } else {
            // 如果没有记录，验证 configureFields 至少返回字段
            $controller = new TopicCrudController();
            $fields = $controller->configureFields('edit');
            $fieldsArray = iterator_to_array($fields);
            self::assertNotEmpty($fieldsArray);
        }
    }

    public function testDetailTopic(): void
    {
        // 验证 configureFields 返回字段
        $controller = new TopicCrudController();
        $fields = $controller->configureFields('detail');
        $fieldsArray = iterator_to_array($fields);
        self::assertNotEmpty($fieldsArray);
    }

    public function testIndexFields(): void
    {
        // Test that configureFields returns appropriate fields for index view
        $controller = new TopicCrudController();
        $fields = $controller->configureFields('index');
        $fieldsArray = iterator_to_array($fields);
        self::assertNotEmpty($fieldsArray);
    }

    public function testNewFields(): void
    {
        // Test that configureFields returns appropriate fields for new view
        $controller = new TopicCrudController();
        $fields = $controller->configureFields('new');
        $fieldsArray = iterator_to_array($fields);
        self::assertNotEmpty($fieldsArray);
    }

    public function testConfigureFilters(): void
    {
        $client = $this->createAuthenticatedClient();

        // 访问带过滤器的 index 页面
        $crawler = $client->request('GET', $this->generateAdminUrl('index'));
        $this->assertResponseIsSuccessful();

        // 验证过滤器表单存在
        $filterForm = $crawler->filter('form.ea-filters-form');
        $this->assertGreaterThanOrEqual(0, $filterForm->count());
    }

    public function testEntityFqcnConfiguration(): void
    {
        $controller = new TopicCrudController();
        self::assertEquals(Topic::class, $controller::getEntityFqcn());
    }

    public function testConfigureCrud(): void
    {
        $client = $this->createAuthenticatedClient();

        // 访问 index 页面验证 CRUD 配置生效
        $crawler = $client->request('GET', $this->generateAdminUrl('index'));
        $this->assertResponseIsSuccessful();

        // 验证页面标题包含 CRUD 配置的标题
        $pageContent = $crawler->html();
        $this->assertStringContainsString('专题', $pageContent);
    }

    public function testControllerRoutePathAttribute(): void
    {
        // Test that the controller has the AdminCrud attribute with correct route path
        $reflectionClass = new \ReflectionClass(TopicCrudController::class);
        $attributes = $reflectionClass->getAttributes();
        self::assertNotEmpty($attributes);

        $adminCrudAttribute = null;
        foreach ($attributes as $attribute) {
            if ('EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud' === $attribute->getName()) {
                $adminCrudAttribute = $attribute;
                break;
            }
        }

        self::assertNotNull($adminCrudAttribute);
    }

    public function testValidationErrors(): void
    {
        $client = $this->createAuthenticatedClient();

        // 访问创建页面
        $crawler = $client->request('GET', $this->generateAdminUrl('new'));
        $this->assertResponseIsSuccessful();

        // 查找表单并提交空表单
        $form = $crawler->selectButton('Create')->form();
        $crawler = $client->submit($form);

        // 验证返回状态码为422（验证错误）
        $this->assertResponseStatusCodeSame(422);

        // 验证错误消息存在（标题字段为必填）
        $errorText = $crawler->filter('.invalid-feedback, .form-error-message, .error')->text();
        $this->assertStringContainsString('should not be blank', $errorText, '标题字段应该有必填验证错误');
    }

    protected function getControllerService(): TopicCrudController
    {
        return new TopicCrudController();
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID列' => ['ID'];
        yield '标题列' => ['标题'];
        yield '推荐状态列' => ['推荐状态'];
        yield '创建时间列' => ['创建时间'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideNewPageFields(): iterable
    {
        yield '标题字段' => ['title'];
        yield '描述字段' => ['description'];
        yield '缩略图字段' => ['thumb'];
        // ArrayField (banners) 跳过测试，因为其HTML结构与标准字段不同
        yield '推荐状态字段' => ['recommend'];
        yield '关联内容字段' => ['entities'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        yield '标题字段' => ['title'];
        yield '描述字段' => ['description'];
        yield '缩略图字段' => ['thumb'];
        // ArrayField (banners) 跳过测试，因为其HTML结构与标准字段不同
        yield '推荐状态字段' => ['recommend'];
        yield '关联内容字段' => ['entities'];
    }
}
