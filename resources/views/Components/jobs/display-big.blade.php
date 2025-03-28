<div class="flex gap-4 bg-white/15 py-4 px-2 rounded">
    <img src="https://placehold.co/80" alt="logo">
    <div class="w-full">
        <div class="flex justify-between">
            <p>{{ fake()->company() }}</p>
            <div class="flex gap-2">
                <div class="rounded border border-white/30 px-2">Remote</div>
                <div class="rounded border border-white/30 px-2">{{ fake()->numberBetween(10, 24) }} Hours Ago</div>
            </div>
        </div>
        <h1 class="font-bold text-2xl">{{ fake()->jobTitle() }}</h1>
        <div class="mt-8 flex items-center justify-between">
            <p>{{ fake()->sentence(6) }}</p>
            <div class="flex gap-2">
                <x-tag>{{ fake()->word() }}</x-tag>
                <x-tag>{{ fake()->word() }}</x-tag>
                <x-tag>{{ fake()->word() }}</x-tag>
            </div>
        </div>
    </div>
</div>