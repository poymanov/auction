<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="auth_user_networks", uniqueConstraints={
 *      @ORM\UniqueConstraint(columns={"network_name", "network_identity"})
 * })
 */
class UserNetwork
{
    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @var string
     */
    private string $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="networks")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @var User
     */
    private User $user;

    /**
     * @ORM\Embedded(class="Network")
     * @var Network
     */
    private Network $network;

    /**
     * @param User $user
     * @param Network $network
     */
    public function __construct(User $user, Network $network)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->user = $user;
        $this->network = $network;
    }

    /**
     * @return Network
     */
    public function getNetwork(): Network
    {
        return $this->network;
    }
}
