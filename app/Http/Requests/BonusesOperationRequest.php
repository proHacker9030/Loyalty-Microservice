<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Infomatika\Microservice\Sdk\Loyalty\Dto\Request\RequestData;

class BonusesOperationRequest extends OrderRequest
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
        return array_merge(parent::rules(), [
            RequestData::BONUSES_AMOUNT_KEY => 'required|numeric',
        ]);
    }
}
