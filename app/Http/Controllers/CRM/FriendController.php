<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\CRM\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function index(Request $request)
    {
        return responder()->success([
            'friends' => Auth::user()->acceptedFriends()->get()
        ]);
    }

    public function add(Request $request, int $friendId)
    {
        if ($friendId === Auth::id()) {
            return responder()->error(500)->respond(500);
        }

        if (!User::find($friendId)) {
            return responder()->error(404)->respond(404);
        }

        try {
            Auth::user()->friends()->syncWithoutDetaching([$friendId]);
            return responder()->success()->respond(200);
        } catch (\Exception $e) {
            return responder()->error(500)->respond(500);
        }
    }

    public function remove(Request $request, int $friendId)
    {
        try {
            if (!Auth::user()->acceptedFriends()->find($friendId)) {
                return responder()->error(404)->respond(404);
            }

            Auth::user()->friends()->detach($friendId);
        } catch (\Exception $e) {
            return responder()->error(500)->respond(500);
        }

        return responder()->success()->respond(200);
    }

    public function accept(Request $request, int $friendId)
    {
        try {
            if (!Auth::user()->notAcceptedFriends()->find($friendId)) {
                return responder()->error(404)->respond(404);
            }

            Auth::user()->friends()->updateExistingPivot($friendId, [
                'accepted' => 1
            ]);
            return responder()->success()->respond(200);
        } catch (\Exception $e) {
            return responder()->error(500)->respond(500);
        }
    }
}
