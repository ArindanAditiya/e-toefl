<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Contracts\User\UserInterface as UserService;
use App\Http\Requests\User\UserRequest;
use Illuminate\Support\Arr;

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
        return inertia('Admin/User/User/Index', [
            'users' => $this->userService->getAllPaginatedWithParams($request)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia('Admin/User/User/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        try {
            #store
            $this->userService->create($request);

            #Bump....
            return redirect()->route('admin.users.index');

        } catch(\Exception $e) {
            #get message
            session()->flash('failed', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            #redirect
            return redirect()->back()->withInput($request->all());
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
        if(!$this->userService->find($id)) return abort('404', 'uppss....');

        $user = $this->userService->find($id);
        return inertia('Admin/User/User/Show', [
            'user' => $user,
            'verificationLink' => urldecode(rtrim(config('app.url') ?: env('APP_URL', ''), '/').'/user/'.$user->id.'/activation')
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

        return inertia('Admin/User/User/Edit', [
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
            return redirect()->route('admin.users.index');

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
            return redirect()->route('admin.users.index');

        } catch(\Exception $e) {
            #get message
            session()->flash('failed', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            #redirect
            return redirect()->back()->withInput($request->all());
        }
    }

    public function activationReminder($id)
    {
        if(!$this->userService->find($id)) return abort('404', 'uppss....');

        $user = $this->userService->find($id);

        $message = "*Mohon dibaca dan dipahami!*\n\n_Hallo, saya admin dari e-TOEFL, akun anda telah terdaftar di platform kami, berikut akun anda._\n\nNama: ".$user->name."\nEmail: ".$user->email."\n\n*saat ini anda belum mengaktifkan akun anda, apakah ada kendala ketika aktivasi ? atau belum mendapatkan link aktivasi ?*\n\n *jika tidak diaktivasi maka dalam jangka waktu lama akun akan terhapus secara otomatis.*\n\n_terimakasih telah menjadi bagian dari kami, semoga e-TOEFL dapat membantu proses belajar anda. aamiin._";
        
        sendWhatsappNotification($user->student->phone_number, $message);    

        return redirect()->back()->with('success', 'Reminder telah dikirim.');
    }

    public function memberReminder($id)
    {
        if(!$this->userService->find($id)) return abort('404', 'uppss....');

        $user = $this->userService->find($id);

        $message = "*Mohon dibaca dan dipahami!*\n\n_Hallo, saya admin dari e-TOEFL, akun anda telah terdaftar di platform kami, berikut akun anda._\n\nNama: ".$user->name."\nEmail: ".$user->email."\n\nsaat ini anda belum mengaktifkan member anda, yuk ambil kesempatan karena ada voucher member gratis untuk anda jika masih bingung, silakan lihat panduan berikut ini.\n\nPanduan Penggunaan Web:\nbit.ly/panduan-etoefl\n\nPanduan Pengerjaan Soal:\nbit.ly/panduan-soal-etoefl\n\n_terimakasih telah menjadi bagian dari kami, semoga e-TOEFL dapat membantu proses belajar anda. aamiin._";
        
        sendWhatsappNotification($user->student->phone_number, $message);    

        return redirect()->back()->with('success', 'Reminder Member telah dikirim.');
    }
}
