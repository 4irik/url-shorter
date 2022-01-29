<?php

declare(strict_types=1);

class GetLinksCest
{
    public function _before(FunctionalTester $I): void
    {
    }

    public function single(FunctionalTester $I): void
    {
        $I->sendGet('links/abc3');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->canSeeResponseContainsJson(
            [
                'id' => 'abc3',
                'long_url' => 'https://google.com',
                'title' => 'Some title',
                'tags' => [
                    'search_engines',
                    'google',
                ],
            ]
        );
    }

    public function notFound(FunctionalTester $I): void
    {
        $I->sendGet('links/abc4');
        $I->seeResponseCodeIs(404);
    }

    public function all(FunctionalTester $I): void
    {
        $I->sendGet('links');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->canSeeResponseContainsJson(
            [
                [
                    'id' => 'fds3',
                    'long_url' => 'https://google.com',
                    'title' => 'Google',
                    'tags' => [
                        'search_engines',
                        'google',
                    ],
                ],
                [
                    'id' => 'hj5g',
                    'long_url' => 'https://yandex.ru',
                    'title' => 'Yandex',
                    'tags' => [
                        'search_engines',
                        'yanxde',
                    ],
                ],
            ]
        );
    }

}
