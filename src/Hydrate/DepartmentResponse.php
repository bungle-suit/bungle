<?php
declare(strict_types=1);

namespace Bungle\DingTalk\Hydrate;

use Bungle\DingTalk\Hydrate\Traits\DepartmentCommonFields;
use Bungle\DingTalk\Hydrate\Traits\ErrorCodeMessage;

/**
 * Map to response of dingtalk requests that require a single document.
 */
class DepartmentResponse
{
    use DepartmentCommonFields, ErrorCodeMessage;

    // 部门的主管列表，取值为由主管的userid组成的字符串，不同的userid使用“|”符号进行分割
    public string $deptManagerUseridList = '';

    // 当前部门在父部门下的所有子部门中的排序值
    public int $order = 0;

    /**
     * Parse $deptManagerUseridList field, split into user ids.
     *
     * @return string[]
     */
    public function getManagerUserIds(): array
    {
        if (!$this->deptManagerUseridList) {
            return [];
        }

        return explode('|', $this->deptManagerUseridList);
    }
}
