<?php
/**
 * FILE TITLE GOES HERE
 *
 * DESCRIPTION OF THE PURPOSE AND USE OF THE CODE
 * MAY BE MORE THAN ONE LINE LONG
 * KEEP LINE LENGTH TO NO MORE THAN 96 CHARACTERS
 *
 * Filename:        HomeController.php
 * Location:
 * Project:         XXX-SaaS-Vanilla-MVC-YYYY-SN
 * Date Created:    20/08/2024
 *
 * Author:          Adrian Gould <Adrian.Gould@nmtafe.wa.edu.au>
 *
 */

namespace App\Controllers;


use Framework\Database;

class HomeController
{
    protected $db;

    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    /*
     * Show the latest products
     *
     * @return void
     */
    public function index()
    {
        $simpleRandomSixQuery = 'SELECT * FROM products ORDER BY RAND() LIMIT 0,1';

        $products = $this->db->query($simpleRandomSixQuery)
            ->fetchAll();


        loadView('home', [
            'product' => $products[0]
        ]);
    }

    /*
     * Show the latest products
     *
     * @return void
     */
    public function dashboard()
    {
        $lastSixQuery = 'SELECT * FROM products ORDER BY created_at DESC LIMIT 0,6';
        $simpleRandomSixQuery = 'SELECT * FROM products ORDER BY RAND() LIMIT 0,6';

        $products = $this->db->query($simpleRandomSixQuery)
            ->fetchAll();

        $productCount = $this->db->query('SELECT count(id) as total FROM products ')
            ->fetch();

        $userCount = $this->db->query('SELECT count(id) as total FROM users')
            ->fetch();

        loadView('dashboard', [
            'products' => $products,
            'productCount' => $productCount,
            'userCount' => $userCount,
        ]);
    }


}