<?php

namespace App\Http\Requests\Admin;

use App\Support\SanitizesRichText;
use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    use SanitizesRichText;

    public function authorize(): bool
    {
        return true; // route middleware already handles auth + role
    }

    /**
     * Strip content_en/content_ar down to the formatting the admin rich-text
     * toolbar can actually produce, before validation runs. Prevents a
     * trusted-but-not-fully-trusted editor/admin account from storing
     * arbitrary HTML/JS that later executes unescaped on the Post show page.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'content_en' => $this->sanitizeRichText($this->input('content_en')),
            'content_ar' => $this->sanitizeRichText($this->input('content_ar')),
        ]);
    }

    public function rules(): array
    {
        return [
            'title_en' => ['required', 'string', 'max:300'],
            'title_ar' => ['required', 'string', 'max:300'],
            'excerpt_en' => ['nullable', 'string', 'max:500'],
            'excerpt_ar' => ['nullable', 'string', 'max:500'],
            'content_en' => ['required', 'string'],
            'content_ar' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'status' => ['required', 'in:draft,published,archived'],
            'is_featured' => ['boolean'],
            'published_at' => ['nullable', 'date'],
            'image' => ['nullable', 'image', 'max:5120'],  // 5 MB
            'remove_image' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title_en.required' => 'The English title is required.',
            'title_ar.required' => 'العنوان بالعربية مطلوب.',
            'content_en.required' => 'The English content is required.',
            'content_ar.required' => 'المحتوى بالعربية مطلوب.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category does not exist.',
            'image.max' => 'Cover image must be under 5 MB.',
        ];
    }
}
