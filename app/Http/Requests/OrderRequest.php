<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Infomatika\Microservice\Sdk\Loyalty\Dto\Request\RequestData;

class OrderRequest extends MainRequest
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
            RequestData::ORDER_KEY => 'required|array',
            RequestData::ORDER_KEY . '.id' => 'required|integer',
            RequestData::ORDER_KEY . '.amount' => 'required|numeric',
        ];
    }
}
