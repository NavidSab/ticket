<?php

namespace Modules\Ticket\Repositories;

use Modules\Ticket\Entities\Ticket;

class TicketRepo
{


    public function getAll()
    {
        return Ticket::all();
    }
    public function store($request)
    {
        $fileName = time().'_'.$request->attachment->getClientOriginalName();
        $filePath = $request->file('attachment')->storeAs('uploads', $fileName, 'public');

        return Ticket::create([
            'name'              =>$request->name,
            'title'             =>$request->title,
            'content'           =>$request->content,
            'email'             =>$request->email,
            'attachment'        =>$fileName
            ]);
    }
    public function show($id)
    {
        return Ticket::where('id',$id)->first();
    }

}
