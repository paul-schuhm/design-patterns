# Pattern Observer

Utilisez le patron de conception Observateur quand des modifications de l’état d’un objet peuvent en impacter d’autres, et que l’ensemble des objets n’est pas connu à l’avance ou qu’il change dynamiquement.

## Exercices / Améliorations

Ici le problème du DP original (de base) c'est qu'il faut penser à notifier les suscribers quand l'état change (c'est à nous d'appeler `notifySuscribers()`). On aimerait que la notification se fasse *automatiquement* au changement d'état, sans avoir à appeler `notifySuscribers` nous même.

Comment implémenter cela ? Une proposition avec `ChangeNotifier` qui encapsule l'état et prévient le `Publisher` quand l'état change. Cette implémentation s'inspire de l'implémentation du pattern faites dans le framework Flutter de Dart.