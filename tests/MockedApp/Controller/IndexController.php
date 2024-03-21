<?php

namespace EightyfourTests\MockedApp\Controller;

use Eightyfour\Abstract\AbstractController;
use Eightyfour\Attribute\Router\Route;
use Eightyfour\Core\Response\Result;

#[Route(path: '/_fuzzy_tests', name: 'fuzzy_unit-tests_')]
class IndexController extends AbstractController
{
    #[Route(path: '', name: 'default', methods: ['GET', 'POST'])]
    public function default(): Result
    {
        return new Result(data: ['result' => 'OK']);
    }
}