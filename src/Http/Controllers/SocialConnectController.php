<?php

namespace Leafwrap\SocialConnects\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Str;
use Leafwrap\SocialConnects\Facades\SocialConnect;
use Leafwrap\SocialConnects\Http\Requests\SocialConnectCallbackRequest;
use Leafwrap\SocialConnects\Http\Requests\SocialConnectRequest;
use Leafwrap\SocialConnects\Models\SocialConnectTracker;
use Leafwrap\SocialConnects\Traits\Helper;

class SocialConnectController extends Controller
{
    use Helper;

    public function socialCall(SocialConnectRequest $request)
    {
        try {
            $res = SocialConnect::connect($request->input('gateway'));

            if ($res['isError']) {
                return $this->leafwrapMessage($res['message'], $res['statusCode'], $res['status']);
            }

            return $this->leafwrapEntity($res['data'], $res['statusCode'], $res['status'], $res['message']);
        } catch (Exception $e) {
            return $this->leafwrapServerError($e);
        }
    }

    public function socialCallback(SocialConnectCallbackRequest $request)
    {
        try {
            $res = SocialConnect::user($request->input('gateway'), $request->input('code'));

            if ($res['isError']) {
                return $this->leafwrapMessage($res['message'], $res['statusCode'], $res['status']);
            }

            $tracker = SocialConnectTracker::query()->create(array_merge($request->validated(), ['response' => $res['data'], 'unique_id' => Str::random()]));
            $url     = config('social-connects.frontend_url') . config('social-connects.login_url') . "?tracker_id={$tracker->unique_id}";
            return redirect()->away($url);
        } catch (Exception $e) {
            return $this->leafwrapServerError($e);
        }
    }
}
