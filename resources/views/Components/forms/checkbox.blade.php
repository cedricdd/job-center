@props(["label", "inputName", "checked" => false])

<div class="flex items-center mb-4">
    <input name="{{ $inputName }}" id="{{ $inputName }}" type="checkbox" value="1" @checked($checked) {{ $attributes->merge(['class' => "w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500"]) }} >
    <label for="{{ $inputName }}" class="ms-2 text-sm font-bold">{{ $label }}</label>
</div>
<x-forms.error name="{{ $inputName }}" />