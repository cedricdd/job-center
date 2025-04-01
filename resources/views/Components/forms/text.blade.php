@props(["label", "inputName"])

<label for="{{ $inputName }}" class="block mb-2 text-sm font-bold text-gray-900 text-white">{{ $label }}</label>
<textarea name="{{ $inputName }}" id="{{ $inputName }}" {{ $attributes->merge(['class' => "bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"]) }}>{{ $slot }}</textarea>
<x-forms.error name="{{ $inputName }}" />