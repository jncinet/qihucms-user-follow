<?php

namespace Qihucms\UserFollow\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FollowIndexRequest extends FormRequest
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
            'user_id' => ['required', 'exists:users,id'],
            'type' => ['required', 'in:follow,fans'],
            'status' => ['filled', 'in:1,2'],
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute未设置',
            'exists' => ':attribute不存在',
            'in' => ':attribute只能为:values',
        ];
    }

    public function attributes()
    {
        return [
            'user_id' => '会员',
            'type' => '读取类型',
            'status' => '关注类型',
        ];
    }
}