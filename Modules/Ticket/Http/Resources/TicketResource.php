<?php
namespace Modules\Ticket\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class TicketResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "TicketID"        => $this->id,
            "Name"            => $this->name,
            "Email"           => $this->email,
            "Title"           => $this->email,
            "Content"         => $this->content,
            "Attachment"      => $this->attachment,
            "Ctreated At"     => $this->created_at,
   
        ];
    }

}
