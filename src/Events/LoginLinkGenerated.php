<?php


namespace Koost89\LoginLinks\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LoginLinkGenerated
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $id;
    public $class;

    public function __construct($id, $class)
    {
        $this->id = $id;
        $this->class = $class;
    }
}
