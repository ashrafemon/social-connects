<?php

namespace Leafwrap\SocialConnects\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Leafwrap\SocialConnects\Http\Requests\SocialGatewayRequest;
use Leafwrap\SocialConnects\Models\SocialGateway;
use Leafwrap\SocialConnects\Traits\Helper;

class SocialGatewayController extends Controller
{
    use Helper;

    public function index()
    {
        try {
            $offset    = request()->input('offset') ?? 15;
            $fields    = ['id', 'type', 'gateway', 'credentials', 'additional', 'status'];
            $condition = [];

            $query = SocialGateway::query();

            if (request()->has('status') && request()->input('status')) {
                $condition['status'] = (int) request()->input('status');
            }

            if (request()->has('get_all') && (int) request()->input('get_all') === 1) {
                $query = $query->select($fields)->where($condition)->get();
            } else {
                $query = $this->leafwrapPaginate($query->select($fields)->where($condition)->latest()->paginate($offset)->toArray());
            }

            return $this->leafwrapEntity($query);
        } catch (Exception $e) {
            return $this->leafwrapServerError($e);
        }
    }

    public function store(SocialGatewayRequest $request)
    {
        try {
            if (SocialGateway::query()->create($request->validated())) {
                return $this->leafwrapMessage('Social gateway created successfully', 201, 'success');
            }
        } catch (Exception $e) {
            return $this->leafwrapServerError($e);
        }
    }

    public function show(string $id)
    {
        try {
            $fields = ['id', 'credentials', 'additional', 'status'];

            if (!$query = SocialGateway::query()->select($fields)->where(['id' => $id])->first()) {
                return $this->leafwrapMessage();
            }
            return $this->leafwrapEntity($query);
        } catch (Exception $e) {
            return $this->leafwrapServerError($e);
        }
    }

    public function update(SocialGatewayRequest $request, string $id)
    {
        try {
            if (!$query = SocialGateway::query()->where(['id' => $id])->first()) {
                return $this->leafwrapMessage();
            }

            $query->update($request->validated());
            return $this->leafwrapMessage('Social gateway updated successfully', 200, 'success');
        } catch (Exception $e) {
            return $this->leafwrapServerError($e);
        }
    }

    public function destroy(string $id)
    {
        try {
            if (!$query = SocialGateway::query()->where(['id' => $id])->first()) {
                return $this->leafwrapMessage();
            }

            $query->delete();
            return $this->leafwrapMessage('Social gateway deleted successfully', 200, 'success');
        } catch (Exception $e) {
            return $this->leafwrapServerError($e);
        }
    }
}
