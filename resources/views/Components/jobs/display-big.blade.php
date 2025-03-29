<x-card>
    <div class="self-center">
        <x-placeholder-image size="90" />
    </div>
    <div class="flex-1">
        <p class="text-gray-100">{{ fake()->company() }}</p>
        <h1 class="mt-3 font-bold text-2xl">{{ fake()->jobTitle() }}</h1>
        <p class="mt-8">{{ fake()->sentence(6) }}</p>
    </div>
    <div class="flex flex-col justify-between items-end">
        <div class="flex gap-2">
            <div class="rounded border border-white/30 px-2">Remote</div>
            <div class="rounded border border-white/30 px-2">{{ fake()->numberBetween(10, 24) }} Hours Ago</div>
        </div>
        <div class="flex gap-2">
            <x-tag>{{ fake()->word() }}</x-tag>
            <x-tag>{{ fake()->word() }}</x-tag>
            <x-tag>{{ fake()->word() }}</x-tag>
        </div>
    </div>
</x-card>