<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\DTO\RequestApiData;
use Illuminate\Foundation\Http\FormRequest;

class MainRequest extends FormRequest
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
        ];
    }

    public function getDto(): RequestApiData
    {
        $dto = new RequestApiData();
        $dto->load($this);

        return $dto;
    }
}
