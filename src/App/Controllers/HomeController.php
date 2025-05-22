<?php

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\TransactionService;

class HomeController{
    public function __construct(
        private TemplateEngine $view,
        private TransactionService $transactionService)
    {
    }
    public function home()
    {
        $page = $_GET['p'] ?? 1;
        $page = (int) $page;
        $length = 3;
        $offset = ($page - 1) * $length;
        $searchTerm = ($_GET['s'] ?? null);


        [$transactions, $count] = $this->transactionService->getUserTransactions(
            $length,
            $offset
        );

        $lastpage = ceil($count / $length);
        

            echo $this->view->render("/index.php", [
            'title' => 'Home Page',
            'transactions' => $transactions,
            'currentPage' => $page,
            'previousPageQuery' => http_build_query([
                'p' => $page - 1,
                's' => $searchTerm
            ]),
                'lastPage' => $lastpage,
                'nextPageQuery' => http_build_query([
                    'p' => $page + 1,
                    's' => $searchTerm
                ])
        ]);

    }
}
