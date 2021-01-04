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
            $tag->save();
        }
    }
}
