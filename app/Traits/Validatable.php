<?php


namespace App\Traits;


trait Validatable
{
    private function validateMessage()
    {
        $this->validate(request(), [
            'subject' => 'required|min:3|max:30',
            'body' => 'required|min:6',
        ]);
    }
}
