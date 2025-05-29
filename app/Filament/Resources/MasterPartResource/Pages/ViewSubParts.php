<?php

namespace App\Filament\Resources\MasterPartResource\Pages;

use App\Filament\Resources\MasterPartResource;
use Filament\Resources\Pages\Page;
use App\Models\MasterPart;

class ViewSubParts extends Page
{
    protected static string $resource = MasterPartResource::class;
 
    public ?MasterPart $masterPart = null;

    protected static string $view = 'filament.resources.master-part-resource.pages.view-sub-parts';

    public function mount($part_number)
    {
        $this->masterPart = MasterPart::where('part_number', $part_number)->firstOrFail();
    }
}
