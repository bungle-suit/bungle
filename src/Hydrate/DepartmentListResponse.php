<?php
declare(strict_types=1);

namespace Bungle\DingTalk\Hydrate;

use Bungle\DingTalk\Hydrate\Traits\ErrorCodeMessage;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * Hydrated DingTalk list response.
 */
class DepartmentListResponse
{
    use ErrorCodeMessage;

    /**
     * @var Department[]
     * @SerializedName("department")
     */
    public array $departments = [];

    /**
     * @internal adder for PropertyAccess and normalizer
     */
    private function addDepartment(Department $dep): void
    {
        $this->departments[] = $dep;
    }
}
