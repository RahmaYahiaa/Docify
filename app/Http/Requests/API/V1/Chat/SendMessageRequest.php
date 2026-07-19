<?php

namespace App\Http\Requests\API\V1\Chat;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'body' => ['nullable', 'string', 'max:5000', 'required_if:type,text', 'prohibited_if:type,voice',],
            'type' => ['nullable', 'string', Rule::in(['text', 'image', 'file', 'voice']),],
            'attachment' => [
                'nullable',
                'file',
                'max:10240',
                'required_if:type,image',
                'required_if:type,file',
                'required_if:type,voice',
                ...match ($this->input('type') ?? 'text') {
                    'image' => ['mimes:jpg,jpeg,png,gif,webp'],
                    'voice' => ['mimes:m4a,mp3,wav,ogg'],
                    'file'  => ['mimes:pdf,doc,docx,txt,zip,rar'],
                    default => ['mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt,zip,rar,m4a,mp3,wav'],
                },
            ],

            'voice_duration' => ['nullable', 'integer', 'min:1', 'max:3600'],
        ];
    }

    public function messages(): array
    {
        $type    = $this->input('type') ?? 'text';
        $allowed = match ($type) {
            'image' => 'jpg, jpeg, png, gif, webp',
            'voice' => 'm4a, mp3, wav, ogg',
            'file'  => 'pdf, doc, docx, txt, zip, rar',
            default => 'mimes:jpg,jpeg,png,gif,pdf,doc,docx,txt,zip,rar,m4a,mp3,wav',
        };

        return [
            'conversation_id.required' => 'Conversation is required.',
            'conversation_id.exists'   => 'Conversation not found.',
            'body.required_if'         => 'Message body is required for text messages.',
            'body.prohibited_if'       => 'Voice messages cannot contain text.',
            'attachment.required_if'   => 'Attachment is required for this message type.',
            'attachment.max'           => 'Attachment may not exceed 10MB.',
            'attachment.mimes'         => "Invalid file type. Allowed for {$type}: {$allowed}.",
        ];
    }
}
