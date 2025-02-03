@extends('components.layouts.app')

@section('content')
<h2>إدخال البيانات للمخططات والوكالات والعينات والمواصفات</h2>

<div>

    @if (class_exists($multiForm))
    <p>تم تحميل MultiForm: {{ $multiForm }}</p>
    @else
    <p>تعذر العثور على كلاس MultiForm.</p>
    @endif
</div>
@endsection
  
<form wire:submit="save">
    <input type="text" wire:model="form.title">
    <div>
        @error('form.title') <span class="error">{{ $message }}</span> @enderror
    </div>

    <input type="text" wire:model="form.content">
    <div>
        @error('form.content') <span class="error">{{ $message }}</span> @enderror
    </div>

    <button type="submit">Save</button>
</form>