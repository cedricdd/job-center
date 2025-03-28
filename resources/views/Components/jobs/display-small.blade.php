<div class="bg-white/10 p-4 rounded flex flex-col text-center min-w-[400px]">
    <div class="self-start">{{ fake()->company() }}</div>
    <div class="my-4">
        <h3 class="text-lg">{{ fake()->jobTitle() }}</h3>
        <p>{{ fake()->sentence(5) }}</p>
    </div>
    <div class="mt-4 inline-flex justify-between items-center mt-auto">
        <div class="flex gap-4">
            <x-tag>Tag</x-tag>
            <x-tag>Tag</x-tag>
            <x-tag>Tag</x-tag>
        </div>
        <img src="https://placehold.co/42" alt="logo">
    </div>
</div>