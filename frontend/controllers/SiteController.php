<?php
namespace frontend\controllers;

use common\services\BookCatalogService;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }


    public function actionIndex()
    {
        $client = new BookCatalogService();
        dump($client->getBooks());
        dump($client->getAuthors());
        dump($client->getAuthors());
        dump('Nil Stevenson author id is - ' . $client->getAuthorId('Nil Stevenson'));
        dump('William Gibson author id is - ' . $client->getAuthorId('William Gibson'));
        dump('Roman Pekarskyi author id is - ' . ($client->getAuthorId('Roman Pekarskyi') ?? 'undefined'));
        echo 'Nil Stevenson books:';
        dump($client->getBooksByAuthor($client->getAuthorId('Nil Stevenson')));
        echo 'William Gibson books:';
        dump($client->getBooksByAuthor($client->getAuthorId('William Gibson')));
        echo 'Roman Pekarskyi books:';
        dump($client->getBooksByAuthor($client->getAuthorId('Roman Pekarskyi')));
        die();
    }
}
