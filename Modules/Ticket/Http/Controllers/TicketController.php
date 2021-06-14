<?php

namespace Modules\Ticket\Http\Controllers;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Ticket\Http\Resources\TicketResource;
use Modules\Ticket\Repositories\TicketRepo;
use App\Response\AjaxResponse;
use Illuminate\Http\Request;
use Modules\Ticket\Http\Requests\ValidateTicketRequest;

class TicketController extends Controller
{
    public $ticketRepo;
    public function __construct(TicketRepo $ticketRepo)
    {
    $this->ticketRepo=$ticketRepo;
    }

    public function index()
    {
    $response = $this->ticketRepo->getAll();
    return view('ticket::index', ['data' => $response]);
    }

    public function store(ValidateTicketRequest $request)
    {
    $response = $this->ticketRepo->store($request);
    return redirect()->back()->with('message', 'IT WORKS!');
    }

    public function show($id)
    {
    $response = $this->ticketRepo->show($id);
    return view('ticket::show', ['data' => $response]);
    }

}