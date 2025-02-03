<?php
namespace App\Filament\SuperAdmin\Resources\ProjectResource\Pages;

use App\Livewire\MultiForm;
use Illuminate\Contracts\View\View;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\SuperAdmin\Resources\ProjectResource;

class ProjectIndex extends ViewRecord
{
    protected static string $resource = ProjectResource::class;

    protected static string $view = 'livewire.multi-form';
    public function render(): View
    {
        return view(static::$view, [
            'multiForm' => MultiForm::class
        ]);
    }
}