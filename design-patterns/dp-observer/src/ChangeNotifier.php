<?php

/**
 * Classe qui maintient et encapsule un état et notifie le Publisher lorsque cet état change
 */

namespace Paul\DP;

class ChangeNotifier
{

    public function __construct(private $state, private Publisher $publisher)
    {
        $this->state = $state;
        $this->publisher = $publisher;
    }

    public function change($newState)
    {
        $this->state = $newState;
        if (isset($this->publisher))
            $this->publisher->notifySuscribers($this->state);
    }
}
