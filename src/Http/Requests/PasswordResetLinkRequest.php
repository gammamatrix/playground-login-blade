<?php
/**
 * Playground
 */
namespace Playground\Login\Blade\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * \Playground\Login\Blade\Http\Requests\PasswordResetLinkRequest
 */
class PasswordResetLinkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return empty($this->user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
        ];
    }
}
