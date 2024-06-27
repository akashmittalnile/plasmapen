<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use Illuminate\Support\Facades\Log;

class AnnouncementController extends Controller
{


    // Controller method to handle AJAX request for announcements
public function announcements(Request $request)
{
    try {
        if ($request->ajax()) {
            $query = Announcement::query();

            // Apply search filter if present
            if ($request->filled('search')) {
                $searchTerm = '%' . $request->search . '%';
                $query->where('title', 'like', $searchTerm)
                    ->orWhere('description', 'like', $searchTerm);
            }

            // Apply date filter if present
            if ($request->filled('date')) {
                $query->whereDate('created_at', $request->date);
            }

            // Paginate the results
            $data = $query->paginate(config('constant.paginatePerPage', 10));

            // Build the HTML response for each announcement
            $html = '';
            foreach ($data as $announcement) {
                $status = $announcement->status ? 'Active' : 'Inactive';
                $image = $announcement->image ? assets('public/uploads/profile/' . $announcement->image) : assets('public/assets/images/no-image.jpg');

                $html .= "
                   <div class='col-md-12'>
                       <div class='notification-box'>
                           <div class='manage-notification-item'>
                               <div class='manage-notification-icon'>
                                   <a href='javascript:void(0)'><img src='" . assets('assets/images/announcement.svg') . "'></a>
                               </div>
                               <div class='manage-notification-content'>
                                   <div class='notification-tags'>{$announcement->title}</div>
                                   <div class='notification-descr'>
                                       <p>{$announcement->description}</p>
                                   </div>
                                   <div class='notification-date'>Announced on: " . \Carbon\Carbon::parse($announcement->created_at)->format('m-d-Y h:iA') . "</div>
                               </div>
                               <div class='manage-announcement-action'>
                                  <a class='editbtn' href='javascript:void(0)' data-id='{$announcement->id}'><img src='" . assets('assets/images/edit1.svg') . "'></a>
                                   <form action='" . route('admin.announcements.delete', ['id' => $announcement->id]) . "' method='POST' id='deleteForm_{$announcement->id}' class='delete-form'>
                                       " . csrf_field() . "
                                       " . method_field('DELETE') . "
                                       <div class='deletebtm' onclick='confirmDelete(\"{$announcement->id}\")'>
                                           <a href='javascript:void(0)'><img src='" . assets('assets/images/trash1.svg') . "'></a>
                                       </div>
                                   </form>
                               </div>
                           </div>
                       </div>
                   </div>";
            }

            // Prepare the response with pagination details
            $response = [
                'currentPage' => $data->currentPage(),
                'lastPage' => $data->lastPage(),
                'total' => $data->total(),
                'html' => $html,
            ];

            return response()->json(['status' => 'success', 'data' => $response]);
        }

        // Render the announcement list page if the request is not AJAX
        $announcements = Announcement::paginate(config('constant.paginatePerPage', 10));
        return view('pages.announcement.list', compact('announcements'));

    } catch (\Exception $e) {
        Log::error('Exception occurred:', ['message' => $e->getMessage()]);
        return response()->json(['status' => 'error', 'message' => 'Exception: ' . $e->getMessage()]);
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
            // Log the error for debugging
            Log::error('Announcement creation failed: ' . $e->getMessage());

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
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Example validation for image upload
            'id' => 'required',
        ]);
    
        // Find the announcement by ID
        $announcement = Announcement::findOrFail($request->id);
    
        // Delete old image if a new image is uploaded
        if ($request->hasFile("image")) {
            // Remove old image if it exists
            if ($announcement->image) {
                fileRemove("/uploads/announcement/image/{$announcement->image}");
            }
    
            // Upload new image
            $image = fileUpload($request->image, "/uploads/announcement/image");
            $announcement->image = $image;
        }
    
        // Update other announcement fields
        $announcement->title = $request->title;
        $announcement->description = $request->description;
        $announcement->status = $request->status === 'active' ? 1 : 0;
        $announcement->is_homepage = $request->show_on_home_page === 'show' ? 1 : 0; // Assuming show_on_home_page is boolean
    
        // Save the updated announcement
        $announcement->save();
    
        return redirect()->route('admin.announcement.list')->with('success', 'Announcement updated successfully.');
    }
    
    
}