<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title_en'   => ['required', 'string', 'max:500'],
            'title_ar'   => ['required', 'string', 'max:500'],
            'body_en'    => ['nullable', 'string'],
            'body_ar'    => ['nullable', 'string'],
            'type'       => ['required', 'in:banner,popup,sidebar,inline'],
            'status'     => ['required', 'in:active,paused,expired'],
            'starts_at'  => ['nullable', 'date'],
            'ends_at'    => ['nullable', 'date', 'after_or_equal:starts_at'],
            'url'        => ['nullable', 'url', 'max:2048'],
            'image'      => ['nullable', 'image', 'max:3072'],  // 3 MB
            'remove_image' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title_en.required' => 'The English title is required.',
            'title_ar.required' => 'العنوان بالعربية مطلوب.',
            'ends_at.after_or_equal' => 'End date must be on or after the start date.',
        ];
    }
}
