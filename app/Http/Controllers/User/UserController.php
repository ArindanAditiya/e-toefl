<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Contracts\User\UserInterface as UserService;
use App\Http\Requests\User\UserRequest;
use Illuminate\Support\Arr;
use Auth;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id = Auth::user()->id;

        if(!$this->userService->find($id)) return abort('404', 'uppss....');

        return inertia('User/User/Index', [
            'user' => $this->userService->find($id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!$this->userService->find($id)) return abort('404', 'uppss....');

        return inertia('User/User/Edit', [
            'user' => $this->userService->find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        try {
            if(!$this->userService->find($id)) return abort('404', 'uppss....');

            $input = $request->all();
            if (!empty($input['password'])) {
                $input['password'] = bcrypt($input['password']);
            } else {
                $input = Arr::except($input,array('password'));
            }

            #update
            $this->userService->update($input, $id);

            #Bump....
            return redirect()->route('user.users.index');

        } catch(\Exception $e) {
            #get message
            session()->flash('failed', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            #redirect
            return redirect()->back()->withInput($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            #delete
            $this->userService->delete($id);

            #Bump....
            return redirect()->route('user.users.index');

        } catch(\Exception $e) {
            #get message
            session()->flash('failed', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            #redirect
            return redirect()->back()->withInput($request->all());
        }
    }
}
