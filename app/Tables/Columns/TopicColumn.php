<?php

namespace App\Tables\Columns;

use Filament\Tables\Columns\Column;

class TopicColumn extends Column
{
    protected string $view = 'tables.columns.topic-column';


    public function openViewModal()
    {
        dd("ok");
    }


}
