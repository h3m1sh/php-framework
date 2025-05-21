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
        $transactions = $this->transactionService->getUserTransactions();
        echo $this->view->render("/index.php", [
            'title' => 'Home Page',
            'transactions' => $transactions
        ]);

    }
}
