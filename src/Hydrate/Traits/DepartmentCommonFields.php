<?php

declare(strict_types=1);

namespace Bungle\DingTalk\Hydrate\Traits;

use Symfony\Component\Serializer\Annotation\SerializedName;

trait DepartmentCommonFields
{
    public int $id;
    public string $name;
    /** @SerializedName("parentid") */
    public int $parentId = 0;

    /** @SerializedName("outerDept") */
    public bool $isOuterDepartment;
    public string $ext = '';
    public string $deptManagerUseridList = '';
    public string $sourceIdentifier = '';

    public static function create(int $id, string $name, int $parentId): self
    {
        $r = new self();
        $r->id = $id;
        $r->name = $name;
        $r->parentId = $parentId;

        return $r;
    }
}
