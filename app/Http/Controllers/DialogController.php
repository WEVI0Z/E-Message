<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ConversationUser;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DialogController extends Controller
{
    function createConversation(Request $request) {
        $user_id = User::query()
                    ->where("access_token", "=", $request->headers->all()["token"][0])
                    ->get()[0]
                    ->id;

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

    function getConversations(Request $request) {
        $user = User::with("conversations")
                ->get()
                ->where("access_token", "=", $request->headers->all()["token"][0]);

        $conversations = $user[0]->conversations;

        return $conversations;
    }

    function getConversation(Request $request) {
        $conversation_id = $request->route("conversationId");

        $conversation = Conversation::with(["messages", "users"])->find($conversation_id);

        return $conversation;
    }

    function sendMessage(Request $request) {
        $validator = Validator::make($request->all(), [
            "text" => "required",
        ]);

        if($validator->fails()) {
            return response([
                'status' => 'Validator exception',
                'errors' => $validator->errors()
            ], 400);
        }

        $user_id = User::query()
                    ->where("access_token", "=", $request->headers->all()["token"][0])
                    ->get()[0]
                    ->id;

        $conversation_id = $request->route("conversationId");

        $message = Message::create([
            "user_id" => $user_id,
            "conversation_id" => $conversation_id,
            "text" => $request->text
        ]);

        return $message;
    }

    function searchUsers(Request $request) {
        $user_id = User::query()
                    ->where("access_token", "=", $request->headers->all()["token"][0])
                    ->get()[0]
                    ->id;

        $users = User::query()
                    ->where("login", "LIKE", "%" . $request->text . "%")
                    ->whereNot("id", "=", $user_id)
                    ->get();
        
        return $users;
    }

    function findConversation(Request $request) {
        $validator = Validator::make($request->all(), [
            "user_id" => "required|numeric",
        ]);

        if($validator->fails()) {
            return response([
                'status' => 'Validator exception',
                'errors' => $validator->errors()
            ], 400);
        }

        $userId = User::query()
                    ->where("access_token", "=", $request->headers->all()["token"][0])
                    ->get()[0]
                    ->id;

        $targetId = $request->user_id;
        

        $users = Conversation::with(["users", "messages"])
                        ->whereHas("users", function($query) use ($userId) {
                            $query->where("user_id", "=", $userId);
                        })
                        ->whereHas("users", function($query) use ($targetId) {
                            $query->where("user_id", "=", $targetId);
                        })
                        ->get();
        
        return $users;
    }
}
