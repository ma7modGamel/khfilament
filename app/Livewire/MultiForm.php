<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;

class MultiForm extends Component
{
    public $scheme_name;
    public $scheme_file;
    public $scheme_status;
    public $project_id;

    public $agency_name;
    public $agency_file;

    public $sample_name;
    public $sample_file;
    public $sample_status;

    public $specification_name;
    public $specification_file;
    public $specification_status;
    public function render()
    {
        return view('livewire.multi-form');
    }
    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('scheme_name')
                ->label('اسم المخطط')
                ->required(),
            Forms\Components\FileUpload::make('scheme_file')
                ->label('ملف المخطط')
                ->required(),
            Forms\Components\TextInput::make('scheme_status')
                ->label('حالة المخطط')
                ->required(),

            Forms\Components\TextInput::make('agency_name')
                ->label('اسم الوكالة الحكومية')
                ->required(),
            Forms\Components\FileUpload::make('agency_file')
                ->label('ملف الوكالة الحكومية')
                ->required(),

            Forms\Components\TextInput::make('sample_name')
                ->label('اسم العينة')
                ->required(),
            Forms\Components\TextInput::make('sample_status')
                ->label('حالة العينة')
                ->required(),
            Forms\Components\FileUpload::make('sample_file')
                ->label('ملف العينة')
                ->required(),

            Forms\Components\TextInput::make('specification_name')
                ->label('اسم المواصفة')
                ->required(),
            Forms\Components\TextInput::make('specification_status')
                ->label('حالة المواصفة')
                ->required(),
            Forms\Components\FileUpload::make('specification_file')
                ->label('ملف المواصفة')
                ->required(),
        ];
    }

    public function submit()
    {
        $scheme = Scheme::create([
            'name' => $this->scheme_name,
            'file' => $this->scheme_file,
            'status' => $this->scheme_status,
            'project_id' => $this->project_id,
        ]);

        $agency = ExtracGovernmentAgency::create([
            'name' => $this->agency_name,
            'file' => $this->agency_file,
            'project_id' => $this->project_id,
        ]);

        $sample = Sample::create([
            'name' => $this->sample_name,
            'file' => $this->sample_file,
            'status' => $this->sample_status,
            'project_id' => $this->project_id,
        ]);

        $specification = Specification::create([
            'name' => $this->specification_name,
            'status' => $this->specification_status,
            'file' => $this->specification_file,
            'project_id' => $this->project_id,
        ]);

        session()->flash('message', 'تم إضافة البيانات بنجاح!');
    }
    
}