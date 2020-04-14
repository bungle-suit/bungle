<?php
declare(strict_types=1);

namespace Bungle\DingTalk\Hydrate;

use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * Map to dingtalk user record.
 */
class User
{
    /** @SerializedName("userid") */
    public string $id;
    public string $name;
    public string $mobile = '';
    // 是否已激活
    public bool $active = false;

    /**
     * @var string[]
     * @SerializedName("department")
     */
    public array $departments;
}
