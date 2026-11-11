<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Contracts\MasterData\StudentInterface as StudentService;
use App\Services\Contracts\User\UserInterface as UserService;
use App\Http\Requests\MasterData\StudentRequest;

class StudentController extends Controller
{
    protected $studentService, $userService;

    public function __construct(StudentService $studentService, UserService $userService)
    {
        $this->studentService = $studentService;
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Admin/MasterData/Student/Index', [
            'users' => $this->userService->getAllUserStudentSimplePaginatedWithParams($request)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!$this->studentService->find($id)) return abort('404', 'uppss....');

        return inertia('Admin/MasterData/Student/Show', [
            'student' => $this->studentService->find($id)
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
        if(!$this->studentService->find($id)) return abort('404', 'uppss....');

        return inertia('Admin/MasterData/Student/Edit', [
            'student' => $this->studentService->find($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            if(!$this->studentService->find($id)) return abort('404', 'uppss....');

            #update
            $this->studentService->update($request, $id);

            #Bump....
            return redirect()->route('admin.students.index');

        } catch(\Exception $e) {
            #get message
            session()->flash('failed', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            #redirect
            return redirect()->back()->withInput($request->all());
        }
    }
}
