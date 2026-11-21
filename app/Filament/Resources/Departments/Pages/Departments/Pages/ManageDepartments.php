<?php

namespace App\Filament\Resources\Departments\Pages; // 👈 แก้ไขโครงสร้าง Namespace ให้ถูกต้อง

use App\Filament\Resources\Departments\DepartmentResource;
use SolutionForest\FilamentTree\Resources\Pages\TreePage;

class ManageDepartments extends TreePage
{
    protected static string $resource = DepartmentResource::class;

    // กำหนดความลึกสูงสุดของโครงสร้างต้นไม้ (ปรับตามโครงสร้างสถาบัน เช่น 3 หรือ 4 ระดับ)
    protected static int $maxDepth = 3;

    protected function getTreeToolbarActions(): array
    {
        return [];
    }

    protected function getActions(): array
    {
        return [
            $this->getCreateAction(),
        ];
    }

    /**
     * แสดงข้อความชื่อหน่วยงานและรหัสบนบล็อกแผนผังต้นไม้
     */
    public function getTreeRecordTitle(?\Illuminate\Database\Eloquent\Model $record = null): string
    {
        // 1. ดักจับกรณีข้อมูลไม่มีอยู่จริง
        if (! $record) {
            return '(ไม่มีข้อมูล)';
        }

        // 2. ดึงค่าชื่อหน่วยงาน หากในฐานข้อมูลเป็น null ให้เปลี่ยนเป็นข้อความ string ทันที
        $name = $record->name ?? '(ไม่ได้ระบุชื่อหน่วยงาน)';

        // 3. ตรวจสอบรหัสหน่วยงาน
        return $record->code
            ? (string) "{$name} [{$record->code}]"
            : (string) $name;
    }

    protected function hasDeleteAction(): bool
    {
        // ปรับเป็น true หากต้องการให้สามารถกดลบหน่วยงานจากหน้า Tree View ได้เลย
        return true;
    }

    protected function hasEditAction(): bool
    {
        return true;
    }

    protected function hasViewAction(): bool
    {
        return false;
    }

    protected function getHeaderWidgets(): array
    {
        return [];
    }

    protected function getFooterWidgets(): array
    {
        return [];
    }
}
