<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Contracts\MasterData\AnnouncementInterface as AnnouncementService;

class AnnouncementController extends Controller
{
    protected $announcementService;

    public function __construct(AnnouncementService $announcementService)
    {
        $this->announcementService = $announcementService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('User/Announcement/Index', [
            'announcements' => $this->announcementService->getAllPaginatedWithParams($request)
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
        if(!$this->announcementService->find($id)) return abort('404', 'uppss....');

        return inertia('User/Announcement/Show', [
            'announcement' => $this->announcementService->find($id),
        ]);
    }
}
