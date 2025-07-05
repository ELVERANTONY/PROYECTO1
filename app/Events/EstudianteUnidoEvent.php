<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EstudianteUnidoEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $estudiante;
    public $claseId;

    /**
     * Create a new event instance.
     *
     * @param User $estudiante
     * @param int $claseId
     */
    public function __construct(User $estudiante, $claseId)
    {
        $this->estudiante = [
            'id' => $estudiante->id,
            'name' => $estudiante->name,
            'avatar_url' => $estudiante->avatar_url,
            'character_class' => $estudiante->character_class
        ];
        $this->claseId = $claseId;
        
        // No retrasar el evento
        $this->dontBroadcastToCurrentUser();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('clase.' . $this->claseId),
        ];
    }
    
    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'EstudianteUnido';
    }
    
    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'estudiante' => $this->estudiante,
            'clase_id' => $this->claseId,
            'timestamp' => now()->toDateTimeString()
        ];
    }
}
