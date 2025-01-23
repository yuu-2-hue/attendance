<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DetailRequest extends FormRequest
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
            'at_work_time' => ['required', 'before:finish_work_time'],
            'finish_work_time' => ['required', 'after:at_work_time'],
            'at_rest_time.*' => ['required', 'before:finish_rest_time.*', 'before:finish_work_time', 'after:at_work_time'],
            'finish_rest_time.*' => ['required', 'after:at_rest_time.*', 'before:finish_work_time', 'after:at_work_time'],
            'reason' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'at_work_time.required' => '出勤時間を入力してください',
            'at_work_time.before' => '出勤時間もしくは退勤時間が不適切な値です',
            'finish_work_time.required' => '退勤時間を入力してください',
            'finish_work_time.after' => '出勤時間もしくは退勤時間が不適切な値です',

            'at_rest_time.*.required' => '休憩開始時間を入力してください',
            'at_rest_time.*.before' => '休憩開始時間が勤務時間外です',
            'at_rest_time.*.after' => '休憩開始時間が勤務時間外です',

            'finish_rest_time.*.required' => '休憩終了時間を入力してください',
            'finish_rest_time.*.before' => '休憩終了時間が勤務時間外です',
            'finish_rest_time.*.after' => '休憩終了時間が勤務時間外です',

            'reason.required' => '備考を記入してください',
        ];
    }
}
