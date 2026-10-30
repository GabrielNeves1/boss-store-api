<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\LoginUser;
use App\Actions\Auth\LogoutUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Response;

final class AuthenticatedSessionController extends Controller
{
    public function store(LoginRequest $request, LoginUser $loginUser): UserResource
    {
        $user = $loginUser->handle($request->email(), $request->password(), $request->remember());

        return new UserResource($user);
    }

    public function destroy(LogoutUser $logoutUser): Response
    {
        $logoutUser->handle();

        return response()->noContent();
    }
}
