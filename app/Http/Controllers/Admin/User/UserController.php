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
        $appUrl = rtrim(config('app.url') ?: env('APP_URL', ''), '/');

        sendBrevoNotification(
            $user->email,
            $user->name,
            "Pengingat: Aktivasi Akun e-TOEFL Ma'had Aly Tebuireng",
            [
                "Akun e-TOEFL Anda telah terdaftar, namun saat ini status akun Anda masih <strong>belum diaktifkan</strong>.",
                "Silakan lakukan aktivasi segera agar Anda dapat mengakses paket soal ujian dan fitur simulasi TOEFL/TOEIC."
            ],
            [
                'Nama Peserta' => htmlspecialchars($user->name),
                'Email' => htmlspecialchars($user->email),
            ],
            $appUrl . "/user/" . $user->id . "/activation",
            "Aktifkan Akun Sekarang",
            "Jika akun tidak diaktifkan dalam kurun waktu tertentu, sistem dapat menonaktifkan data secara otomatis."
        );

        return redirect()->back()->with('success', 'Email reminder aktivasi telah berhasil dikirim ke ' . $user->email);
    }

    public function memberReminder($id)
    {
        if(!$this->userService->find($id)) return abort('404', 'uppss....');

        $user = $this->userService->find($id);
        $appUrl = rtrim(config('app.url') ?: env('APP_URL', ''), '/');

        sendBrevoNotification(
            $user->email,
            $user->name,
            "Pengingat: Aktivasi Status Member & Voucher Ujian e-TOEFL",
            [
                "Saat ini akun Anda belum mengaktifkan paket voucher ujian e-TOEFL.",
                "Manfaatkan kesempatan untuk mengasah kemampuan bahasa Inggris Anda dengan beragam paket try out yang telah disiapkan oleh Ma'had Aly Hasyim Asy'ari Tebuireng."
            ],
            [
                'Nama Peserta' => htmlspecialchars($user->name),
                'Email' => htmlspecialchars($user->email),
            ],
            $appUrl . "/user/dashboard",
            "Masuk ke Dashboard Ujian",
            "Tersedia panduan lengkap pengerjaan soal dan aktivasi voucher di dashboard pengguna."
        );

        return redirect()->back()->with('success', 'Email reminder member telah berhasil dikirim ke ' . $user->email);
    }
}
