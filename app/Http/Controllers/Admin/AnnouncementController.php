<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use Illuminate\Support\Facades\Log;

class AnnouncementController extends Controller
{

    public function announcements(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = Announcement::query();

                if ($request->filled('search')) {
                    $searchTerm = '%' . $request->search . '%';
                    $query->where('title', 'like', $searchTerm)
                        ->orWhere('description', 'like', $searchTerm);
                }

                if ($request->filled('date')) {
                    $query->whereDate('created_at', $request->date);
                }

                // Paginate the results
                $data = $query->paginate(config('constant.paginatePerPage', 10));
                $html = '';
                foreach ($data as $key => $val) {
                    $html .= "<div class='col-md-12'>
                    <div class='manage-notification-item'>
                        <div class='manage-notification-icon'>
                            <img src='" . assets('assets/images/announcement.svg') . "'>
                        </div>
                        <div class='manage-notification-content'>
                            <div class='notification-descr'>
                                <h6 class='p-0'>$val->title</h6>
                                <p>$val->description</p>
                            </div>
                            <div class='notification-date'>Announced on: " . \Carbon\Carbon::parse($val->created_at)->format('m-d-Y h:iA') . "</div>
                        </div>
                        <div class='manage-announcement-action'>
                            <a class='editbtn' href='javascript:void(0)' data-id='{$val->id}'><img src='" . assets('assets/images/edit1.svg') . "'></a>
                            <form action='" . route('admin.announcements.delete', ['id' => $val->id]) . "' method='POST' id='deleteForm_{$val->id}' class='delete-form'>
                                " . csrf_field() . "
                                " . method_field('DELETE') . "
                                <div class='deletebtm' onclick='confirmDelete(\"{$val->id}\")'>
                                    <a href='javascript:void(0)'><img src='" . assets('assets/images/trash1.svg') . "'></a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>";
                }

                $response = array(
                    'currentPage' => $data->currentPage(),
                    'lastPage' => $data->lastPage(),
                    'total' => $data->total(),
                    'html' => $html,
                );
                return successMsg('Notification', $response);
            }
            return view('pages.announcement.list');
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $announcement = new Announcement();
            $announcement->title = $request->title;
            $announcement->description = $request->description;
            $announcement->status = $request->status === 'active' ? 1 : 0;
            $announcement->is_homepage = $request->show_on_home_page ? 1 : 0;

            if ($request->hasFile("image")) {
                $image = fileUpload($request->image, "/uploads/announcement/image");
                $announcement->image = $image;
            }

            $announcement->save();

            return redirect()->route('admin.announcement.list')->with('success', 'Announcement created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.announcement.list')->with('error', 'Exception: ' . $e->getMessage());
        }
    }

    public function edit(Request $request)
    {
        $announcement = Announcement::findOrFail($request->id);
        return response()->json([
            'status' => 'success',
            'data' => $announcement
        ]);
    }

    public function delete(Request $request, $id)
    {
        try {
            $announcement = Announcement::findOrFail($id);
            if ($announcement->image) {
                fileRemove("/uploads/announcement/image/{$announcement->image}");
            }
            $announcement->delete();
            return redirect()->route('admin.announcement.list')->with('success', 'Announcement deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete announcement.');
        }
    }

    public function update(Request $request)
    {
        // Validate input data
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:active,inactive',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', 
            'id' => 'required',
        ]);

        $announcement = Announcement::findOrFail($request->id);

        // Delete old image if a new image is uploaded
        if ($request->hasFile("image")) {
            if ($announcement->image) {
                fileRemove("/uploads/announcement/image/{$announcement->image}");
            }

            // Upload new image
            $image = fileUpload($request->image, "/uploads/announcement/image");
            $announcement->image = $image;
        }

        $announcement->title = $request->title;
        $announcement->description = $request->description;
        $announcement->status = $request->status === 'active' ? 1 : 0;
        $announcement->is_homepage = $request->show_on_home_page === 'show' ? 1 : 0; // Assuming show_on_home_page is boolean
        $announcement->save();

        return redirect()->route('admin.announcement.list')->with('success', 'Announcement updated successfully.');
    }


}