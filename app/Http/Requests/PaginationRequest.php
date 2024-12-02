<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @className: PaginationRequest
 * @Description: 通用的分页请求接收类
 * @CreatedBy: lisongkun
 * @CreatedAt: 2024/7/11 16:50
 */
class PaginationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'pageNo' => 'required|integer|min:1',
            'pageSize' => 'required|integer|min:1',
            'queryParams' => 'nullable',
        ];
    }

    public function validated()
    {
        $data = parent::validated();
        if(!array_key_exists('queryParams', $data)) {
            $data['queryParams'] = '{}';
        }
        return $data;
    }

    public function messages()
    {
        return [
            'pageNo.required' => '页码不能为空',
            'pageNo.integer' => '页码必须是整数',
            'pageNo.min' => '页码不能小于1',
            'pageSize.required' => '每页条数不能为空',
            'pageSize.integer' => '每页条数必须是整数',
            'pageSize.min' => '每页条数不能小于1',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
