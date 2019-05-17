<?php


namespace App\Http\Transformers;

class MusicTransformer extends Transformer
{
    public function transformSong(array $song): array
    {
        return [
            'title' => $song['title'],
            'location' => $song['location'],
            'originalName' => $song['originalName'],
            'extension' => $song['extension'],
            'uniqueName' => $song['uniqueName'],
            'artistes' => explode(', ',$song['artistes']),
            'updated_at' => $song['updated_at'],
            'created_at' => $song['created_at'],
            'id' => $song['id']
        ];
    }
}