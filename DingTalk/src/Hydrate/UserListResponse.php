<?php
declare(strict_types=1);

namespace Bungle\DingTalk\Hydrate;

use Bungle\DingTalk\Hydrate\Traits\ErrorCodeMessage;
use Symfony\Component\Serializer\Annotation\SerializedName;

class UserListResponse
{
    use ErrorCodeMessage;

    /**
     * @var User[]
     * @SerializedName("userlist")
     */
    public array $users = [];

    /**
     * @internal adder for PropertyAccess and normalizer
     */
    private function addUser(User $user): void
    {
        $this->users[] = $user;
    }

}
