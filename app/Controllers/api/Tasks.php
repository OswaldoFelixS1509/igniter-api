<?php

namespace App\Controllers\api;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;
use App\Models\Task;

class Tasks extends ResourceController
{
    use ResponseTrait;
    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    protected $format = 'json';

    public function index()
    {
        $model = new Task();
        $data = $model->findAll();
        return $this->respond($data);
    }

    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        $model = new Task();
        $data = $model->find($id);

        if(empty($data))
        {
            return $this->failNotFound('Task not found');
        }

        return $this->respond($data);
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $rules = [
            'name'        => 'required',
            'description' => 'required',
            'due_date'    => 'required|valid_date',
            'status'      => 'required|in_list[pending,in_progress,completed]',
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $data = [
            'name'  =>  $this->request->getVar('name'),
            'description'   =>  $this->request->getVar('description'),
            'due_date'   =>  $this->request->getVar('due_date'),
            'status'   =>  $this->request->getVar('status'),
        ];

        $model = new Task();

        $model->save($data);

        return $this->respondCreated(['message' => 'Task created successfully', 'data' => $data]);
    }

    /**
     * Return the editable properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        $request = service('request'); // Get the request instance

        // Log all request data
        log_message('info', 'Request Data: ' . print_r($request->getRawInput(), true));
        $model = new Task();

        // Validation rules
        $rules = [
            'name'        => 'required',
            'description' => 'required',
            'due_date'    => 'required|valid_date',
            'status'      => 'required|in_list[pending,in_progress,completed]',
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        // Access the form data using getPost instead of getVar
        $data = [
            'name'        => $this->request->getVar('name'),
            'description' => $this->request->getPost('description'),
            'due_date'    => $this->request->getPost('due_date'),
            'status'      => $this->request->getPost('status'),
        ];

        // Log data to check if it's being correctly retrieved
        log_message('debug', 'Data to be updated: ' . print_r($data, true));

        // Update task
        if ($model->update($id, $data)) {
            return $this->respondUpdated(['message' => 'Task updated successfully', 'data' => $data]);
        } else {
            return $this->fail($model->errors());
        }
    }



    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        //
    }
}
