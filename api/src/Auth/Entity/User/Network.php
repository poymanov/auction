<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * @ORM\Embeddable
 */
class Network
{
    /**
     * @ORM\Column(type="string", length=16)
     * @var string
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=16)
     * @var string
     */
    private string $identity;

    /**
     * @param string $name
     * @param string $identity
     */
    public function __construct(string $name, string $identity)
    {
        Assert::notEmpty($name);
        Assert::notEmpty($identity);
        $this->name = $name;
        $this->identity = $identity;
    }

    /**
     * @param Network $network
     * @return bool
     */
    public function isEqualTo(self $network): bool
    {
        return
            $this->getName() === $network->getName() &&
            $this->getIdentity() === $network->getIdentity();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getIdentity(): string
    {
        return $this->identity;
    }
}
