<?php

namespace App\Console\Commands;

use App\Enums\RoomStatus;
use App\Models\Room;
use App\Models\RoomType;
use Gemini\Data\Content;
use Gemini\Data\GenerationConfig;
use Gemini\Data\SafetySetting;
use Gemini\Enums\HarmBlockThreshold;
use Gemini\Enums\HarmCategory;
use Gemini\Enums\Role;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Console\Command;

class GeminiInitializeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gemini:init-chat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize System Value In Gemini';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
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

        $rooms = Room::with('type:id,type')->available(RoomStatus::AVAILABLE)->get(['id', 'room_typeid', 'room_number']);
        logger(json_encode($rooms));
        $chat = $generativeModel->startChat(history: [
            Content::parse(part: json_encode($rooms)),
            Content::parse(part: 'Yes', role: Role::MODEL)
        ]);

        $response = $chat->sendMessage('list 4 room');
        logger($response->text());
    }
}
