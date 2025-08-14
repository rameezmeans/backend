<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ChatgptPrompt;

class ChatgptPromptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prompts = [
            [
                'title' => 'Professional Response',
                'prompt' => 'You are a professional automotive engineer. Please help me rephrase the following response to a client in a {tone} tone while maintaining technical accuracy and clarity: {text}'
            ],
            [
                'title' => 'Technical Explanation',
                'prompt' => 'As an automotive expert, please explain the following technical issue in simple terms that a client can understand: {text}'
            ],
            [
                'title' => 'Customer Service',
                'prompt' => 'Please help me respond to this customer inquiry in a {tone} and helpful manner: {text}'
            ],
            [
                'title' => 'Problem Diagnosis',
                'prompt' => 'Based on the following symptoms, please provide a professional diagnosis and recommended solution: {text}'
            ],
            [
                'title' => 'Quote Response',
                'prompt' => 'Please help me create a professional quote response for the following service request: {text}'
            ]
        ];

        foreach ($prompts as $prompt) {
            ChatgptPrompt::create($prompt);
        }
    }
}
