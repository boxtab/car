<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $users = app('db')->select("SELECT * FROM users WHERE id=" . $_GET['user_id']);
        $user = (array) ($users[0] ?? []);
        if (!empty($user)) {
            $user['items'] = $user['items'] ? json_decode($user['items'], true) : [];
            $items = json_encode(array_merge($user['items'], $_GET['items']));
            app('db')->select("UPDATE users SET items='{$items}', updated_at=NOW() WHERE id=" . $user['id']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $users = app('db')->select("SELECT * FROM users WHERE id=" . $id);
        $user = (array) ($users[0] ?? []);
        $user['total'] = 0;
        if (!empty($user['items'])) {
            $user['items'] = json_decode($user['items'], true);
            foreach ($user['items'] as $itemName => $itemValue) {
                $user['total'] += $itemValue;
            }
        }
        return response($user);
    }
}
