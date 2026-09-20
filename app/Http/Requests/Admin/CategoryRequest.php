<?php
// ─── CategoryRequest ──────────────────────────────────────────────────────
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $categoryId = $this->route('category')?->id;

        return [
            'name_en' => ['required', 'string', 'max:191',
                Rule::unique('categories', 'name_en')->ignore($categoryId)],
            'name_ar' => ['required', 'string', 'max:191',
                Rule::unique('categories', 'name_ar')->ignore($categoryId)],
            'slug'    => ['nullable', 'string', 'max:191', 'alpha_dash',
                Rule::unique('categories', 'slug')->ignore($categoryId)],
        ];
    }

    public function messages(): array
    {
        return [
            'name_en.required' => 'The English name is required.',
            'name_ar.required' => 'الاسم بالعربية مطلوب.',
            'name_en.unique'   => 'A category with this English name already exists.',
            'name_ar.unique'   => 'فئة بهذا الاسم العربي موجودة مسبقاً.',
            'slug.alpha_dash'  => 'Slug may only contain letters, numbers, dashes, and underscores.',
            'slug.unique'      => 'This slug is already taken.',
        ];
    }
}
