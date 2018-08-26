<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreUsersRequest extends FormRequest
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

        switch($this->method())
        {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                    'name' => 'required|min:2',
                    'phone' => 'required|regex:/(05)[0-9]{8}/|unique:users,phone',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required|min:3',
                    'password_confirmation' => 'required|min:3|same:password',
                    'roles' => 'required',
                    'image' =>'mimes:png,jpg,jpeg'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                $user_id = intval($this->user_id) ;
                return [
                    'name' => 'required|min:2',
                    'phone' => 'required|regex:/(05)[0-9]{8}/|unique:users,phone,'.$user_id,
                    //'email' => 'required|email|unique:users,email,'.$user_id,
                    'email' => 'nullable|email|unique:users,email,'.$user_id,
                    'password' => 'confirmed',
                    'image' =>'mimes:png,jpg,jpeg'
                ];
            }
            default:break;
        }
    }
}
