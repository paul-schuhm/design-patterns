<?php

/**
 * Proposition Design Patterns : Exercice 7
 */

class HtmlDocument
{

    //Injection de dépendance via le constructeur
    public function __construct(private HtmlContentStrategy $strategy)
    {
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

interface HtmlContentStrategy
{
    /**
     * Retourne le contenu du document HTML
     */
    public function getContent(): string;
}

//Notre première stratégie
class HelloMessage implements HtmlContentStrategy
{
    public function __construct(
        private string $message
    ) {
    }

    public function getContent(): string
    {
        return "Hello, {$this->message}";
    }
}

//Notre deuxième stratégie
class Announcement implements HtmlContentStrategy
{
    public function getContent(): string
    {
        return '<p style="color:red;">Annonce importante !</p>';
    }
}

// $document = new HtmlDocument();
// $document = new HelloMessage('world !');
// $document = new Announcement();
// echo $document->getHtml();

// $document = new HtmlDocument(new HelloMessage('world !'));
$document = new HtmlDocument(new Announcement());
echo $document->getHtml();
