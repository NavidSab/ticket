<?php

namespace Modules\Ticket\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateTicketRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string',
            'title' => 'required|string',
            'email' => 'required|email',
            'attachment' => 'max:10000|mimes:csv,txt,xlx,xls,pdf',//10 Mb
            'content' => 'string',
        ];
    }

}
