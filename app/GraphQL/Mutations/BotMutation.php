<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Enums\RoomStatus;
use App\Models\Room;
use Gemini\Data\Content;
use Gemini\Data\GenerationConfig;
use Gemini\Data\SafetySetting;
use Gemini\Enums\HarmBlockThreshold;
use Gemini\Enums\HarmCategory;
use Gemini\Enums\Role;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Database\Eloquent\Model;

final readonly class BotMutation
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {   
        // TODO implement the resolver
        $safetySettingDangerousContent = new SafetySetting(
            category: HarmCategory::HARM_CATEGORY_DANGEROUS_CONTENT,
            threshold: HarmBlockThreshold::BLOCK_ONLY_HIGH
        );
        
        $safetySettingHateSpeech = new SafetySetting(
            category: HarmCategory::HARM_CATEGORY_HATE_SPEECH,
            threshold: HarmBlockThreshold::BLOCK_ONLY_HIGH
        );
        
        $generationConfig = new GenerationConfig(
            maxOutputTokens: 2048,
            temperature: 0.1,
            topP: 0.8,
            topK: 10
        );
        
        $generativeModel = Gemini::geminiPro()
         ->withSafetySetting($safetySettingDangerousContent)
         ->withSafetySetting($safetySettingHateSpeech)
         ->withGenerationConfig($generationConfig);

        $rooms = Room::with('type:id,type')->available(RoomStatus::AVAILABLE)->limit(100)->get(['id', 'view', 'price', 'room_typeid', 'room_number']);
        $chat = $generativeModel->startChat(history: [
            Content::parse(part: json_encode($rooms)),
            Content::parse(part: 'Yes', role: Role::MODEL)
        ]);
        return $chat->sendMessage($args['content'])->text();
    }
}
