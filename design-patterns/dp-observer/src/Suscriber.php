<?php

namespace Paul\DP;


class Suscriber implements ISuscriber
{
    public function __construct(private string $name)
    {
    }

    public function update($context)
    {
        echo sprintf("Le suscriber %s a été mis à jour, suite au changement d'état suivant : %s\n", $this->name, $context);
    }
}
