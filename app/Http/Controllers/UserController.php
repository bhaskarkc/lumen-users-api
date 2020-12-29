<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Events\UserEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Creates a new user
     *
     * @param \Illuminate\Http\Request $request
     * @return json
     */
    public function createUser(Request $request)
    {
        $user = User::create($request->all());
        event(new UserEvent('New User Created.', $user));
        return response()->json($user);
    }

    /**
     * Index page for users
     *
     * @param \Illuminate\Http\Request $request
     * @return json
     */
    public function index(Request $request)
    {
        return User::paginate($request->per_page);
    }

    /**
     * Displays user details
     *
     * @return json
     */
    public function show(int $id)
    {
        $user = User::find($id);

        if (is_null($user)) {
            return response()->json(
                ['error' => 'User not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        event(new UserEvent('View User', $user));
        return response()->json($user);
    }

    /**
     * Update user details
     *
     * @param int $id User id
     * @param \Illuminate\Http\Request $request
     * @return json
     */
    public function update(int $id, Request $request)
    {
        $user = User::find($id);

        if (is_null($user)) {
            return response()->json(
                ['error' => 'User not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        $user->fill($request->all());
        $user->save();

        event(new UserEvent('User Updated', $user));
        return response()->json($user);
    }

    /**
     * Deletes a new user
     *
     * @param int $id
     * @return json
     */
    public function destroy(int $id)
    {
        $user = User::find($id);

        if (is_null($user)) {
            return response()->json(
                ['error' => 'User not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        event(new UserEvent('User Deleted', $user));

        $user->delete();
        return response()->json(['message' => 'User deleted.'], Response::HTTP_OK);
    }
}
