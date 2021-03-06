<?php

namespace App\Http\Controllers;

use App\Http\Requests\Taksonomi\CreateTaksonomiRequest;
use App\Services\TaksonomiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class TaksonomiBloomController extends Controller
{
    protected $taksonomiService;

    public function __construct(TaksonomiService $taksonomiService)
    {
        $this->taksonomiService = $taksonomiService;
    }

    public function index()
    {
        try {
            $redis = cache::remember('hai', 6000, function () {
                return DB::table('taksonomis')->get();
            });
// dd($redis);
            Reds::set('some-key', 'test');
            $test = Redis::get('username');
// dd($test, 1);
            $data['remembers'] = $this->taksonomiService->getAll('remember');
            $data['understands'] = $this->taksonomiService->getAll('understand');
            $data['applys'] = $this->taksonomiService->getAll('apply');
            $data['analyzes'] = $this->taksonomiService->getAll('analyze');
            $data['evaluates'] = $this->taksonomiService->getAll('evaluate');
            $data['creates'] = $this->taksonomiService->getAll('create');
            return view('admin.taksonomi.index', $data);
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    public function viewCreate($role)
    {
        try {
            $data['role'] = $role;

            return view('admin.taksonomi.create', $data);
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    public function store(CreateTaksonomiRequest $request)
    {
        try {
            $this->taksonomiService->create($request->validated());

            return redirect('admin/taksonomi-bloom');
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    public function delete($id)
    {
        try {
            $this->taksonomiService->delete($id);

            return redirect('admin/taksonomi-bloom');
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }
}
