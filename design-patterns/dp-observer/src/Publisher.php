<?php

namespace Paul\DP;

/**
 * Gere la liste de suscribers à l'état
 */
class Publisher
{
    private array $suscribers;
    // private $state;
    private ChangeNotifier $state;

    public function __construct($state = "initial state")
    {
        $this->state = new ChangeNotifier($state, $this);
    }

    /**
     * Enregistre de nouveaux suscribers
     */
    public function addSuscribers(ISuscriber ...$suscribers): void
    {
        foreach ($suscribers as $suscriber) {
            $this->suscribers[] = $suscriber;
        }
    }

    public function notifySuscribers($context): void
    {
        foreach ($this->suscribers as $suscriber) {
            $suscriber->update($context);
        }
    }

    public function doSomething()
    {
        $this->state->change("State has changed !");
    }
}
