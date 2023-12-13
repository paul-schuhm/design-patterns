<?php
//Suggestion exercice 7 (commentée, avec chaque étape)

namespace SolutionParHeritage {

    class HtmlDocument
    {
        function getHtml()
        {
            return sprintf("<html><body>%s</body></html>", $this->getContent());
        }
        function getContent()
        {
            return 'Hello !';
        }
    }


    class HelloMessage extends HtmlDocument
    {
        public function __construct(public string $message)
        {
        }

        public function getContent()
        {
            return 'Hello, ' . $this->message . ' !';
        }
    }

    class Announcement extends HtmlDocument
    {
        function getContent()
        {
            return '<p>Annonce importante ! </p>';
        }
    }

    function client()
    {
        $helloWorld = new HelloMessage('world');
        echo $helloWorld->getHtml() . PHP_EOL;
        $annoucement = new Announcement();
        echo $annoucement->getHtml() . PHP_EOL;
    }

    // client();
}


namespace SolutionParStrategy {

    //Solution par pattern Strategy

    class HtmlDocument
    {

        private HtmlContentStrategy $strategy;

        //On utilise l'injection de dépendance dans le constructeur pour donner une stratégie à notre objet
        //On ne le fait plus par héritage (override getContent dans la classe enfant), on le fait par composition
        //HtmlDocument dispose d'une stratégie pour gérer le contenu
        public function __construct(HtmlContentStrategy $strategy)
        {
            $this->strategy = $strategy;
        }

        function getHtml()
        {
            return sprintf("<html><body>%s</body></html>", $this->getContent());
        }

        function getContent()
        {
            return $this->strategy->getContent();
        }
    }

    //On isole la méthode getContent dans une interface (c'est le comportement qui change d'une implémentation à l'autre)
    //Autant de classes que de stratégies (c'est a dire d'implémentations désirées de getContent())

    interface HtmlContentStrategy
    {
        function getContent(): string;
    }

    class HelloContent implements HtmlContentStrategy
    {

        public function __construct(private string $message)
        {
        }

        function getContent(): string
        {
            return sprintf("Hello %s!", $this->message);
        }
    }

    class Annoucement implements HtmlContentStrategy
    {
        function getContent(): string
        {
            return '<p>Annonce importante ! </p>';
        }
    }


    function client()
    {
        $helloWorld = new HtmlDocument(new HelloContent('foo bar'));
        echo $helloWorld->getHtml() . PHP_EOL;
        $annoucement = new HtmlDocument(new Annoucement());
        echo $annoucement->getHtml() . PHP_EOL;
    }

    client();

    //Qu'avons nous gagné ici en passant par le pattern Strategy au lieu de l'héritage ?
    //- Déjà nous avons remplacé l'héritage par la composition. L'héritage crée des couplages forts entre les classes (car les enfants héritent de l'implémentation): il faut override les méthodes parentes, parfois celle-ci n'ont pas de sens chez la classe enfant, etc. Il est difficile dans des situations plus complexes de résoudre ces problème de couplage (rappelez vous le problème Rectangle/Carré !)
    //- Nous pouvons changer facilement "au runtime" d'implémentation (de stratégie) car la classe HtmlDocument n'a plus la responsabilité du contenu, seulement d'appeler la méthode getContent().
    //- Comme nous travaillons vers une interface et non une implémentation (contrairement au cas de l'héritage), nous gardons une interface propre entre HtmlDocument et ses stratégies (getContent() uniquement)
    //- Nous respectons le principe Ouvert/Fermé: on peut ajouter de nouvelles stratégies sans modifier le contexte (ici le contexte est l'objet HtmlDocument). Il serait possible de rajouter un setStrategy pour changer de stratégie sans créer une nouvelle instance de HtmlDocument. Dans le cas de l'héritage nous devons changer d'objet pour changer de contenu
}
