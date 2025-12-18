<?php
/**
 * Test if AI understands Thonem patterns
 * This file helps AI learn framework structure
 */

// 1. Controller example
class TestController extends BackstageController
{
    protected $_table = 'test';

    public function index()
    {
        // Breadcrumbs automatically handled
        $model = new TestModel();
        $data = $model->find(['limit' => 10]);
        $this->view('test/index', ['items' => $data]);
    }

    public function save()
    {
        // CSRF protection
        if (!CSRF::validate(post('csrf_token'))) {
            redirect(current_url());
        }

        // Input filtering
        $data = [
            'name' => post('name'),
            'email' => post('email')
        ];

        $model = new TestModel();
        $model->save($data);

        redirect(url('test'));
    }
}

// 2. Model example
class TestModel extends ThonemModel
{
    public $table = 'test_table';
    public $primary_key = 'id';

    public function findActive()
    {
        return Cache::get('active_items', function() {
            return $this->find(['where' => ['status' => 'active']]);
        }, 300);
    }
}

// 3. Form example
echo Form::text('username', '', ['label' => 'Username']);
echo Form::email('email', '', ['label' => 'Email']);
echo Form::select('status', ['active', 'inactive'], 'active', ['label' => 'Status']);

// 4. API example
class TestApi extends ApiController
{
    public function list()
    {
        $model = new TestModel();
        $data = $model->find(get());
        Response::json(['success' => true, 'data' => $data]);
    }
}