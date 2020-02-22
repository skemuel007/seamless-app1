<?php


namespace App;


use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ToCollection;

class CoursesExport implements ToCollection
{
    use Exportable;

    /**
     * @inheritDoc
     */
    public function collection(Collection $collection)
    {
        return Course::all();
    }
}
