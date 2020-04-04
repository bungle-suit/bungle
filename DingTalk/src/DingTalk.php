<?php
declare(strict_types=1);

namespace Bungle\DingTalk;

use Bungle\DingTalk\Hydrate\Department;
use Bungle\DingTalk\Hydrate\HydrateUtil;
use Bungle\Framework\FP;
use EasyDingTalk\Application;

/**
 * Stateful DingTalk interface, load and cache on need.
 */
class DingTalk
{
    private Application $app;
    private Department $rootDepartment;
    private array $departmentById;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Return root node of department tree.
     */
    public function getDepartmentTree(): Department
    {
        $this->initDepartments();
        return $this->rootDepartment;
    }

    /**
     * Get Department by its id.
     */
    public function getDepartment(int $id): Department
    {
        $this->initDepartments();
        return $this->departmentById[$id];
    }

    private function initDepartments(): void
    {
        if (isset($this->rootDepartment)) {
            return;
        }

        $resp = $this->app->department->list();
        $this->checkResponse($resp);

        $ids = array_map(fn ($rec) => $rec['id'], $resp['department']);
        $list = array_map([$this, 'loadDepartment'], $ids);
        $this->departmentById = array_combine(array_map(FP::attr('id'), $list), array_values($list));
        $this->rootDepartment = Department::toTree($list);
    }

    protected function checkResponse(array $resp): void
    {
        if ($resp['errcode'] !== 0) {
            throw new DingTalkException($resp['errmsg'], $resp['errcode']);
        }
    }

    private function loadDepartment(int $id): Department {
        $resp = $this->app->department->get($id);
        $this->checkResponse($resp);

        /** @var Department $r */
        $r = HydrateUtil::hydrate($resp, Department::class);
        return $r;
    }

}
