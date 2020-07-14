<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\Friend\Destroy;
use App\Http\Requests\Friend\Index;
use App\Http\Requests\Friend\Store;
use App\Models\CRM\User;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function index(Index $request)
    {
        return responder()->success([
            'friends' => Auth::user()->friends()->get()
        ]);
    }

    public function store(Store $request)
    {
        $friendId = (int)$request->validated()['friend_id'];
        if ($friendId === Auth::id()) {
            return responder()->error(500)->respond(500);
        }

        if (!Auth::user()->friends()->find($friendId)) {
            try {
                $friend = User::find($request->validated()['friend_id']);
                Auth::user()->friends()->save($friend);
            } catch (\Exception $e) {
                return responder()->error(500)->respond(500);
            }
        }

        return responder()->success()->respond(200);
    }

    public function destroy(Destroy $request, int $friendId)
    {
        try {
            Auth::user()->friends()->detach($friendId);
        } catch (\Exception $e) {
            return responder()->error(500)->respond(500);
        }

        return responder()->success()->respond(200);
    }
}
