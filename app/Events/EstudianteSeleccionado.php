<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class EstudianteSeleccionado implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $estudiante;
    public $claseId;

    /**
     * Create a new event instance.
     *
     * @param User $estudiante
     * @param int $claseId
     * @return void
     */
    public function __construct($estudiante, $claseId)
    {
        $this->estudiante = $estudiante;
        $this->claseId = $claseId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('clase.' . $this->claseId);
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'estudiante.seleccionado';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'estudiante' => [
                'id' => $this->estudiante->id,
                'name' => $this->estudiante->name,
                'email' => $this->estudiante->email
            ],
            'clase_id' => $this->claseId
        ];
    }
}
