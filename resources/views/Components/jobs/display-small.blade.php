<x-card size="small">
    <div class="self-start">{{ fake()->company() }}</div>
    <div class="my-4">
        <h3 class="text-lg group-hover:text-blue-600">{{ fake()->jobTitle() }}</h3>
        <p class="text-sm mt-4">{{ fake()->sentence(5) }}</p>
    </div>
    <div class="mt-4 inline-flex justify-between items-center mt-auto">
        <div class="flex gap-4">
            <x-tag size="small">Tag</x-tag>
            <x-tag size="small">Tag</x-tag>
            <x-tag size="small">Tag</x-tag>
        </div>
        <x-placeholder-image size="42" />
    </div>
</x-card>