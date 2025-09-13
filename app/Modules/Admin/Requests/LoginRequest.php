<?php
/**
  * User: Mithun
 * Date: 25/05/18
 * Time: 9:27 AM
 */

namespace App\Modules\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class LoginRequest extends FormRequest
{
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
                'email'   => 'required|email',
                'password' => 'required'
            ];

    }

}