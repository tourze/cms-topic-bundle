<?php

declare(strict_types=1);

namespace Tourze\CmsTopicBundle\Entity;

use CmsBundle\Entity\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\CmsTopicBundle\Repository\TopicRepository;
use Tourze\DoctrineIpBundle\Traits\IpTraceableAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;

#[ORM\Entity(repositoryClass: TopicRepository::class)]
#[ORM\Table(name: 'cms_topic', options: ['comment' => '内容专题表'])]
class Topic implements \Stringable
{
    use BlameableAware;
    use IpTraceableAware;
    use TimestampableAware;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private int $id = 0;

    #[Groups(groups: ['admin_curd'])]
    #[ORM\Column(type: Types::STRING, length: 120, unique: true, options: ['comment' => '名称'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 120)]
    private ?string $title = null;

    #[Groups(groups: ['admin_curd'])]
    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '描述'])]
    #[Assert\Length(max: 65535)]
    private ?string $description = null;

    #[Groups(groups: ['admin_curd'])]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '缩略图'])]
    #[Assert\Length(max: 255)]
    private ?string $thumb = null;

    /**
     * @var array<string, mixed>
     */
    #[Groups(groups: ['admin_curd'])]
    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => 'BANNER'])]
    #[Assert\Type(type: 'array')]
    private array $banners = [];

    #[Groups(groups: ['admin_curd'])]
    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['comment' => '是否推荐'])]
    #[Assert\Type(type: 'bool')]
    private ?bool $recommend = null;

    /**
     * @var Collection<int, Entity>
     */
    #[ORM\ManyToMany(targetEntity: Entity::class, fetch: 'EXTRA_LAZY')]
    private Collection $entities;

    public function __construct()
    {
        $this->entities = new ArrayCollection();
    }

    public function __toString(): string
    {
        if (0 === $this->getId()) {
            return '';
        }

        return $this->getTitle() ?? '';
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getThumb(): ?string
    {
        return $this->thumb;
    }

    public function setThumb(?string $thumb): void
    {
        $this->thumb = $thumb;
    }

    /**
     * @return array<string, mixed>
     */
    public function getBanners(): array
    {
        return $this->banners;
    }

    /**
     * @param array<string, mixed>|null $banners
     */
    public function setBanners(?array $banners): void
    {
        $this->banners = $banners ?? [];
    }

    public function getRecommend(): ?bool
    {
        return $this->recommend;
    }

    public function setRecommend(bool $recommend): void
    {
        $this->recommend = $recommend;
    }

    /**
     * @return Collection<int, Entity>
     */
    public function getEntities(): Collection
    {
        return $this->entities;
    }

    public function addEntity(Entity $entity): self
    {
        if (!$this->entities->contains($entity)) {
            $this->entities->add($entity);
        }

        return $this;
    }

    public function removeEntity(Entity $entity): self
    {
        $this->entities->removeElement($entity);

        return $this;
    }

    public function getEntityCount(): int
    {
        return $this->getEntities()->count();
    }
}
