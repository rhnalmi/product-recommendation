<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class MasterPart extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document';
    protected static ?string $navigationGroup = 'Inventory'; 

    protected static string $view = 'filament.pages.inventory-document';
}
