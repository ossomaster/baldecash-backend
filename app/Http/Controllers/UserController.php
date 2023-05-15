<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Mail\AccountCreated;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use function Ramsey\Uuid\v1;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'users' => User::orderBy('created_at', 'DESC')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create($validated);

        // TODO: usar colas para mejorar tiempo de respuesta
        try {
            Mail::to($user)->send(new AccountCreated($user, $validated['password']));
        } catch (\Throwable $th) {

            Log::error('Error al enviar mail', [
                'user' => $user,
                'exception' => $th,
            ]);
        }

        return response()->json([
            'user' => $user,
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        $user->update($validated);

        return response()->json([
            'user' => $user,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {

        if (auth()->user()->id == $user->id) {
            return response()->json([
                'message' => 'No puedes eliminar tu propia cuenta',
            ], 403);
        }

        $user->delete();

        return response()->noContent();
    }
}
