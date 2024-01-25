<?php

namespace App\Http\Requests\v1\Webhook;

use App\Core\Errors;
use App\Exceptions\ValidationException;
use App\Helper\HashHelper;
use Illuminate\Foundation\Http\FormRequest;

class SendRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'hash' => 'required|string',
            'chat_id' => 'required|string',
            'hook' => 'required|string',
            'body' => 'nullable|array',
        ];
    }

    protected function prepareForValidation()
    {
        $hash = $this->route('hash');
        if (!$chatId = HashHelper::getChatIdByHash($hash)) {
            throw new ValidationException('Invalid webhook hash', Errors::VALIDATION_ERROR->value);
        }

        $hook = $this->header('X-Gitlab-Event');
        $body = request()->all();
        $this->merge([
            'hash' => $hash,
            'chat_id' => $chatId,
            'hook' => $hook,
            'body' => $body,
        ]);
    }
}
