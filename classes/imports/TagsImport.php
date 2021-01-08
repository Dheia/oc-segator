<?php namespace Waka\Segator\Classes\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Waka\Segator\Models\Tag;

class TagsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $tag = new Tag();
            $tag->id = $row['id'] ?? null;
            $tag->name = $row['name'] ?? null;
            $tag->slug = $row['slug'] ?? null;
            $tag->is_active = $row['is_active'] ?? null;
            $tag->is_hidden = $row['is_hidden'] ?? null;
            $tag->data_source = $row['data_source'] ?? null;
            $tag->is_manual = $row['is_manual'] ?? null;
            $tag->is_auto_class_calculs = $row['is_auto_class_calculs'] ?? null;
            $tag->class_calculs = $row['class_calculs'] ?? null;
            $tag->parent_incs = json_decode($row['parent_incs'] ?? null);
            $tag->parent_excs = $row['parent_excs'] ?? null;
            $tag->save();
        }
    }
}
