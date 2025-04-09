<?php
/**
 * FILE TITLE GOES HERE
 *
 * DESCRIPTION OF THE PURPOSE AND USE OF THE CODE
 * MAY BE MORE THAN ONE LINE LONG
 * KEEP LINE LENGTH TO NO MORE THAN 96 CHARACTERS
 *
 * Filename:        colourController.php
 * Location:
 * Project:         SaaS-Vanilla-MVC
 * Date Created:    22/08/2024
 *
 * Author:          Adrian Gould <Adrian.Gould@nmtafe.wa.edu.au>
 *
 */

namespace App\Controllers;

use Framework\Authorisation;
use Framework\Database;
use Framework\Session;
use Framework\Validation;
use JetBrains\PhpStorm\NoReturn;
use League\HTMLToMarkdown\HtmlConverter;


class ColourController
{

    protected Database $db;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }


    /**
     * Colour home page
     *
     * @return void
     * @throws \Exception
     */
    public function index(): void
    {
        $filter = "";
        if (isset($_GET['filter']) && strlen($_GET['filter']) > 0) {
            $sql = 'SELECT * FROM colours  WHERE classification LIKE :filter_value';
            $filter = $_GET['filter'];
            $colours = $this->db->query($sql, ["filter_value" => $filter])->fetchAll();
        } else {
            $sql = 'SELECT * FROM colours';
            $colours = $this->db->query($sql)->fetchAll();
        }
        $colour_count = count($colours);

        $sqlColourSelect = "SELECT DISTINCT classification FROM colours";
        $colourSelect = $this->db->query($sqlColourSelect)->fetchAll();

        loadView('colours/index', [
            'colours' => $colours,
            'filter' => $filter,
            'colour_count' => $colour_count,
            'colourSelect' => $colourSelect,
        ]);
    }


    /**
     * Show the create colour form
     *
     * @return void
     */
    public function create(): void
    {

        $sqlColourSelect = "SELECT DISTINCT classification FROM colours";
        $colourSelect = $this->db->query($sqlColourSelect)->fetchAll();

        loadView('colours/create',
            ['colourSelect' => $colourSelect,]
        );
    }


    /**
     * Show a single colour
     *
     * @param array $params
     * @return void
     * @throws \Exception
     */
    public function show(array $params): void
    {
        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $sql = 'SELECT * FROM colours WHERE id = :id';
        $colour = $this->db->query($sql, $params)->fetch();

        // Check if colour exists
        if (!$colour) {
            ErrorController::notFound('colour not found');
            return;
        }

        loadView('colours/show', [
            'colour' => $colour
        ]);
    }

    /**
     * Store data in database
     *
     * @return void
     * @throws \Exception
     */
    #[NoReturn] public function store()
    {
        $allowedFields = ['name', 'hex_code', 'classification'];

        $newcolourData = array_intersect_key($_POST, array_flip($allowedFields));

        $newcolourData = array_map('sanitize', $newcolourData);

        $requiredFields = ['name', 'hex_code'];

        $errors = [];

        foreach ($requiredFields as $field) {
            if (empty($newcolourData[$field]) || !Validation::string($newcolourData[$field])) {
                $errors[$field] = ucfirst($field) . ' is required';
            }
        }

        if (!empty($errors)) {
            // Reload view with errors
            loadView('colours/create', [
                'errors' => $errors,
                'colour' => $newcolourData
            ]);
        }

//        $rgb = hexRgbToDec($)

        // Save the submitted data
        $fields = [];

        foreach ($newcolourData as $field => $value) {
            $fields[] = $field;
        }

        $fields = implode(', ', $fields);

        $values = [];

        foreach ($newcolourData as $field => $value) {
            // Convert empty strings to null
            if ($value === '') {
                $newcolourData[$field] = null;
            }

            $values[] = ':' . $field;
        }

        $values = implode(', ', $values);

        $insertQuery = "INSERT INTO colours ({$fields}) VALUES ({$values})";

        $this->db->query($insertQuery, $newcolourData);

        Session::setFlashMessage('success_message', 'colour created successfully');

        redirect('/colours');
    }

    /**
     * Show the colour edit form
     *
     * @param array $params
     * @return null
     * @throws \Exception
     */
    public function edit(array $params): null
    {
        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $colour = $this->db->query('SELECT * FROM colours WHERE id = :id', $params)->fetch();

        // Check if colour exists
        if (!$colour) {
            ErrorController::notFound('colour not found');
            exit();
        }

        // Authorisation
        if (!Authorisation::isOwner($colour->user_id)) {
            Session::setFlashMessage('error_message',
                'You are not authorized to update this colour');
            return redirect('/colours/' . $colour->id);
        }

        $converter = new HtmlConverter();

        $colour->description = $converter->convert($colour->description ?? '');

        loadView('colours/edit', [
            'colour' => $colour
        ]);
        return null;
    }

    /**
     * Update a colour
     *
     * @param array $params
     * @return null
     * @throws \Exception
     */
    #[NoReturn] public function update(array $params): null
    {
        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $colour = $this->db->query('SELECT * FROM colours WHERE id = :id', $params)->fetch();

        // Check if colour exists
        if (!$colour) {
            ErrorController::notFound('colour not found');
            exit();
        }

        // Authorisation
        if (!Authorisation::isOwner($colour->user_id)) {
            Session::setFlashMessage('error_message',
                'You are not authorised to update this colour');
            return redirect('/colours/' . $colour->id);
        }

        $allowedFields = ['name', 'description', 'price'];

        $updateValues = array_intersect_key($_POST, array_flip($allowedFields)) ?? [];

        $updateValues = array_map('sanitize', $updateValues);

        $requiredFields = ['name', 'price'];

        $errors = [];

        foreach ($requiredFields as $field) {
            if (empty($updateValues[$field]) || !Validation::string($updateValues[$field])) {
                $errors[$field] = ucfirst($field) . ' is required';
            }
        }

        if (!empty($errors)) {
            loadView('colours/edit', [
                'colour' => $colour,
                'errors' => $errors
            ]);
            exit;
        }

        if (isset($updateValues['description'])) {
            $description = $updateValues['description'] ?? '';
            $markdown = htmlToMarkdown($description);
            $updateValues['description'] = $markdown;
        }

        // Submit to database
        $updateFields = [];

        foreach (array_keys($updateValues) as $field) {
            $updateFields[] = "{$field} = :{$field}";
        }

        $updateFields = implode(', ', $updateFields);

        $updateQuery = "UPDATE colours SET $updateFields WHERE id = :id";

        $updateValues['id'] = $id;
        if (isset($updateValues['price'])) {
            $updateValues['price'] = (float)$updateValues['price'] * 100;
        }

        $this->db->query($updateQuery, $updateValues);

        // Set flash message
        Session::setFlashMessage('success_message', 'colour updated');

        redirect('/colours/' . $id);

    }


    /**
     * Search colours by keywords/location
     *
     * @return void
     * @throws \Exception
     */
    public function search(): void
    {
        $keywords = isset($_GET['keywords']) ? trim($_GET['keywords']) : '';

        $query = "SELECT * FROM colours WHERE name LIKE :keywords ";

        $params = [
            'keywords' => "%{$keywords}%",
        ];

        $colours = $this->db->query($query, $params)->fetchAll();

        loadView('/colours/index', [
            'colours' => $colours,
            'keywords' => $keywords,
        ]);
    }

}