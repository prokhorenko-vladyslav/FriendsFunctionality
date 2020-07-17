<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Http\Resources\CRM\FriendCollection;
use App\Models\CRM\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class FriendController
 * @package App\Http\Controllers\CRM
 */
class FriendController extends Controller
{
    /**
     * Method, which returns list of friends
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return responder()->success(
            (new FriendCollection(Auth::user()->acceptedFriends()->paginate(config('app.page_size'))))->response()->getData(true)
        )->respond();
    }

    /**
     * Method, which adds new friend for current user
     *
     * @param Request $request
     * @param int $friendId
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Method, which removes friend of current user
     *
     * @param Request $request
     * @param int $friendId
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Method, which accept friend offer
     *
     * @param Request $request
     * @param int $friendId
     * @return \Illuminate\Http\JsonResponse
     */
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
