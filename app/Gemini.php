<?php

use App\Models\Room;
use Gemini\Data\Content;
use Gemini\Enums\Role;
use Gemini\Laravel\Facades\Gemini as FacadesGemini;
use Gemini\Resources\ChatSession;

class Gemini{
    public function init(): ChatSession{
        $rooms = Room::with('type')->get()->toArray();
        $chat = FacadesGemini::chat()->startChat(history: [
            Content::parse(part: json_encode($rooms)),
            Content::parse(part: 'Yes, I understand.', role: Role::MODEL)
        ]);
        return $chat;
    }
}