<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;

class TransactionService{

    public function __construct(private Database $db){

    }

    public function create(array $formData){
        $formattedDate = "{$formData['date']} 00:00:00";

        $this->db->query(
            "INSERT INTO transactions(user_id, description,amount,date)
            VALUES(:user_id, :description, :amount, :date)",
            [
                'user_id' => $_SESSION['user'],
                'description' => $formData['description'],
                'amount' => $formData['amount'],
                'date' => $formattedDate
            ]
        );

    }

    public function getUserTransactions(string $searchTerm = null)
    {
        // Get search term from parameter instead of directly from $_GET
        $searchTerm = $searchTerm ?? ($_GET['s'] ?? '');

        // Create base query
        $query = "SELECT *, DATE_FORMAT(date, '%d/%m/%Y') AS formatted_date 
              FROM transactions 
              WHERE user_id = :user_id";

        $params = ['user_id' => $_SESSION['user']];

        // Only add description search if search term is provided
        if ($searchTerm !== '') {
            $query .= " AND description LIKE :description";
            $params['description'] = "%$searchTerm%";
        }

        // Add sorting for better user experience
        $query .= " ORDER BY date DESC";

        return $this->db->query($query, $params)->findAll();
    }

}