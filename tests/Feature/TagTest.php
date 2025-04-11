<?php

use App\Models\Tag;

test('tags_index_loads', function () {
    $response = $this->get(route('tags.index'));

    $response->assertStatus(200);
    $response->assertSeeText('Tags Center');
});

test('tags_show_loads', function () {
    $tag = Tag::firstOrCreate(['name' => 'Test Tag']);

    $response = $this->get(route('tags.show', $tag->name));

    $response->assertStatus(200);
    $response->assertSeeText($tag->name);
});

