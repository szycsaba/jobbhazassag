<?php

namespace App\Http\Controllers;

use App\Services\PartnerInviteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InviteController extends Controller
{
    public function store(Request $request, PartnerInviteService $svc): JsonResponse
    {
        if (session('invite_sent')) {
            return response()->json(['ok'=>false,'msg'=>'A meghívót már elküldted.'], 409);
        }

        $data = $request->validate([
            'email'      => ['required','email'],
            'name'       => ['required','string','max:255'],
            'your_name'  => ['required','string','max:255'],
            'session_id' => ['nullable','string'],
        ]);

        [$ok, $msg, $status] = $svc->send(
            $data['email'], $data['your_name'], $data['name']
        );

        if ($ok) {
            session(['invite_sent' => true]);
        }

        return response()->json(['ok' => $ok, 'msg' => $msg, 'status' => $status],
            $ok ? 200 : 502
        );
    }
}
