<?php

namespace App\Http\Controllers\Admins;

use App\Models\Web\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Intervention\Image\Facades\Image;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Storage;
use Purifier;

class WebAdminsController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showEditPage(Request $request, $path='/') {

    	$page = Page::firstOrNew(['path' => $path]);

		$default_page = str_replace("/","_",$path);
		$page->content = Storage::get('default_pages/'.$default_page.'.html');
		$page->path = $path;
		$page->save();

        return view('admin.web.edit', ['page'=>$page]);
    }

    public function storeEditPage(Request $request) {
    	if (!$request->has('path') || !$request->has('content')) {
    		return 'error';
		}

		$path = $request->input('path');

    	//Make sure to sanitize content before storing it
		$cleanContent = Purifier::clean($request->input('content'));

		$page = Page::wherePath($path)->first();

		//Set the title if we received one
		if ($request->has('title')) {
			$page->title = strlen($request->input('title')) ? $request->input('title') : '';
		}
		$page->content = $cleanContent;
		$page->save();

		return redirect($path);
	}

	public function createImage(Request $request) {

		//Make sure file has acceptable extension
		$fileFormat = strtolower($request->file('file')->getClientOriginalExtension());
		$PhotoValidFormat = array('jpg', 'png', 'gif', 'jpeg', 'bmp');
		if (!in_array($fileFormat, $PhotoValidFormat)) {
			return response()->json(['error'=>'invalid_file_format'], 401);
		}
		//Make sure the file doesn't trigger any errors, like file size too big
		if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
			return response()->json(['error'=>'upload_error'], 500);
		}

		//Get the file's original name, strip the extension from it.
		$originalName = $request->file('file')->getClientOriginalName();
		$originalName = substr($originalName, 0, strrpos($originalName, "."));

		//Use date to make folder with taoday's date as the name for uploads
		$d = new \DateTime();

		//Build out the file's path/to/file, both with and without extension
		$fileName = $d->format('Y-m-d') . '/' . $originalName;
		$fileNameWithExt = $fileName . '.' . $fileFormat;

		//Check if file with current name already exists, if it does add (x) to the end, incrementing x for each duplicate
		$i=1;
		while (Storage::disk('public')->exists('uploads/'.$fileNameWithExt)) {
			if (substr($fileName, -1) === ')') {
				$fileName = substr($fileName, 0, strrpos($fileName, "(")) . '(' . $i . ')';
				$fileNameWithExt = $fileName . '.' . $fileFormat;
			}
			else {
				$fileName .= '(1)';
				$fileNameWithExt = $fileName . '.' . $fileFormat;
			}
			$i++;
		}

		//Store the image
		$imagePath = request('file')->storeAs('uploads', $fileNameWithExt, 'public');
		$image = Image::make(public_path("storage/{$imagePath}"));


		//If either height or width is > 1000 then resize down to fit within 1000x1000
		$maxWidth = 1000;
		$maxHeight = 1000;
		$image->height() > $image->width() ? $maxWidth=null : $maxHeight=null;
		$image->resize($maxWidth, $maxHeight, function ($constraint) {
			$constraint->aspectRatio();
			$constraint->upsize();
		});

		//Save image
		if ($image->save()) {
			return response()->json(['success'=>'success', 'location'=>"storage/{$imagePath}"]);
		}

		return response()->json(['error'=>'image_error'], 500);
	}
}
