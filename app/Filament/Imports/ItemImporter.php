<?php

namespace App\Filament\Imports;

use App\Models\Item;
use App\Models\Project;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Hidden;

class ItemImporter extends Importer
{

    protected function afterCreate(): void
    {
    }
    protected static ?string $model = Item::class;
    protected ?int $projectId = null;
    public static function getColumns(): array
    {
        return [
            ImportColumn::make('number'),
            ImportColumn::make('description'),
            ImportColumn::make('unit'),
            ImportColumn::make('quantity'),

            ImportColumn::make('unit_price'),
            ImportColumn::make('discount_percentage'),
            ImportColumn::make('discounted_price'),
            ImportColumn::make('total_price'),
            ImportColumn::make('project')
                ->relationship()

        ];
    }

    public function resolveRecord(): ?Item
    {
        // return Item::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Item();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your item import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
