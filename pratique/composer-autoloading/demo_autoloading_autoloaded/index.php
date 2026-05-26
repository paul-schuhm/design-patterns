<?php

//Charger l'autoloader (contenu de src)
require './vendor/autoload.php';

require_once './db.php';

use Ps\App\Models\Post;

//Préparer un formateur de date, avec arguments nommés
$fmt = new IntlDateFormatter(
    locale: 'fr_FR',
    timezone: 'Europe/Paris',
    pattern: 'd MMMM'
);

//Une fonction pour comparer les posts par date de publication (plus au moins récent)
function cmp_posts_by_date(Post $a, Post $b)
{
    if ($a->date_publication == $b->date_publication) {
        return 0;
    }
    return $a->date_publication > $b->date_publication ? -1 : 1;
}

//Tri du plus récent au plus ancien
usort($posts, "cmp_posts_by_date");

?>

<!-- Partie template -->

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site web</title>
    <style>
        html {
            width: 60%;
            margin: auto;
        }

        body>footer {
            font-size: 7pt;
            font-style: italic;
            text-align: center;
        }

        ul {
            list-style-type: none;
        }
    </style>
</head>

<body>
    <h1> <?php echo POSTS_PER_PAGE; ?> Dernières publications</h1>

    <ul>
        <!-- On voit que PHP n'est pas très adapté comme langage de template (boucle avec contrainte fastidieuse, penser à appeler htmlentities, mélange de balises HTML et PHP difficiles à lire, etc.) On préféra utiliser des moteurs de templates dédiés, comme Twig.-->
        <?php $current_post_index = 0; ?>
        <?php while ($current_post_index < POSTS_PER_PAGE) :
            $post = $posts[$current_post_index];
            ?>
            <li>
                <article id="<?php echo htmlentities($post->id); ?>">
                    <h2><?php echo htmlentities($post->title); ?></h2>
                    <p>
                        <a href="/<?php echo htmlentities($post->slug); ?>">Lire l'article </a>
                    </p>
                    <footer>
                        <p>
                            Publié le
                            <time datetime="<?php echo $post->date_publication->format('d:m:Y H:i'); ?>">
                                <?php echo $fmt->format($post->date_publication); ?>
                            </time>
                            par <?php
                                $author = array_find($users, fn($user) => $user->id == $post->author);
                                printf("%s %s", $author->firstName, $author->lastName);
                            ?>
                        </p>
                    </footer>
                </article>
            </li>

            <?php $current_post_index++; ?>
        <?php endwhile; ?>
    </ul>

    <footer>Ce site web <strong>utilise <em>l'autoloading</em> !</strong></footer>

    <?php
    dump($posts);
    ?>
</body>

</html>
