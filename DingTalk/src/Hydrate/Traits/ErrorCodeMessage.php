<?php
declare(strict_types=1);

namespace Bungle\DingTalk\Hydrate\Traits;

use Bungle\DingTalk\Hydrate\HydrateUtil;
use RuntimeException;
use Symfony\Component\Serializer\Annotation\SerializedName;

trait ErrorCodeMessage
{
    // success if 0, error if other value.
    /** @SerializedName("errcode") */
    public int $errorCode = 0;
    /** @SerializedName("errmsg") */
    public string $errorMessage = '';

    /**
     * Check errcode raise exception if errcode not 0.
     */
    public function checkCode(): void
    {
        $errCode = $this->errorCode;
        $errMsg = $this->errorMessage;
        if (0 !== $errCode) {
            throw new RuntimeException("[DingTalk] $errCode/$errMsg");
        }
    }

    public static function hydrate(array $ddResponse): self
    {
        /** @var self $r */
        $r = HydrateUtil::hydrate($ddResponse, self::class);

        return $r;
    }
}
