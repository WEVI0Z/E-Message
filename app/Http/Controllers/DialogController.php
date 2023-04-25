<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ConversationUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DialogController extends Controller
{
    function createConversation(Request $request) {
        $user_id = User::query()->where("access_token", "=", $request->headers->all()["token"][0])->get()[0]->id;

        $validator = Validator::make($request->all(), [
            "user_id" => "required|integer",
        ]);

        if($validator->fails()) {
            return response([
                'status' => 'Validator exception',
                'errors' => $validator->errors()
            ], 400);
        }

        $guest_user_id = $request->user_id;

        if($guest_user_id == $user_id) {
            return response([
                'status' => "Bad request",
                'error' => "You cannot create conversations with yourself"
            ], 400);   
        }

        $conversation = Conversation::create();

        ConversationUser::create([
            "user_id" => $user_id,
            "conversation_id" => $conversation->id,
        ]);

        ConversationUser::create([
            "user_id" => $guest_user_id,
            "conversation_id" => $conversation->id,
        ]);

        return Conversation::with(["messages", "users"])->find($conversation->id);
    }
}
