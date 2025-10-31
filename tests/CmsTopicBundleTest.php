<?php

declare(strict_types=1);

namespace CmsTopicBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\CmsTopicBundle\CmsTopicBundle;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;

/**
 * @internal
 */
#[CoversClass(CmsTopicBundle::class)]
#[RunTestsInSeparateProcesses]
final class CmsTopicBundleTest extends AbstractBundleTestCase
{
}
