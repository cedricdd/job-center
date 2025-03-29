@props(["label", "inputName"])

<label for="{{ $inputName }}" class="block mb-2 text-sm font-bold text-gray-900 text-white">{{ $label }}</label>
<input id="{{ $inputName }}" {{ $attributes->merge(['class' => "mb-6 bg-gray-700 border border-gray-600 text-gray-400 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed"]) }} value="{{ $slot }}" />