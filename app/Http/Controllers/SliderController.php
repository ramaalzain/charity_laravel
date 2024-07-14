<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
class SliderController extends Controller
{
    public function index(){
        $sliders=slider::latest()->get();
        return response()->json(
            $sliders
            ,200);
    }
    public function store(Request $request){
        
        try{
            
              
            $validateslider = Validator::make($request->all(), 
            [
                'main'=>'required|boolean',
                'title' => 'string|nullable',
                'description' => 'string|nullable',
                'image' => 'file|required|mimetypes:image/jpeg,image/png,image/gif,image/svg+xml,image/webp,application/wbmp',

            ]);
            $validateslider->sometimes('image', 'required|mimetypes:image/vnd.wap.wbmp', function ($input) {
                return $input->file('image') !== null && $input->file('image')->getClientOriginalExtension() === 'wbmp';
            });
       
         
           

            if($validateslider->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'خطأ في التحقق',
                    'errors' => $validateslider->errors()->first()
                ], 422);
            }
      
            if($request->hasFile('image') and $request->file('image')->isValid()){
                $image= $this->store_image($request->file('image')); 
            }
            $slider = slider::create(array_merge(
                $validateslider->validated()
                
                ));
            
            $slider->image=$image;
            $result=$slider->save();
           if ($result){
               
                return response()->json(
                 'تم أضافة بيانات السلايدر بنجاح'
                 , 201);
                }
           else{
                return response()->json('حدث خطأ أثناء أضافة البيانات', 422);
                }

        }
        catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' =>  $th->getMessage(),
                // "حدث خطأ أثناء أضافة البيانات"
            ], 500);
        }
       
        
    }
    public function destroy(Request $request){
        try {  
             
            
            $validate = Validator::make( $request->all(),
                ['id'=>'required|integer|exists:sliders,id']);
            if($validate->fails()){
            return response()->json([
               'status' => false,
               'message' => 'خطأ في التحقق',
               'errors' => $validate->errors()
            ], 422);}
          
            $slider=slider::find($request->id);
         
           
          if($slider){ 
               
            if($slider->image != null){
                $this->deleteImage($slider->image);
            }
                
            $result= $slider->delete();
                if($result){ 
                    return response()->json(
                    ' تم حذف البيانات بنجاح'
                    , 200);
                }
            }

            return response()->json(null, 422);
        }
        catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } 
        catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while deleting the slider.'], 500);
        }
    }
    public function update(Request $request){
        try{
           
                
           
            
            $validateslider = Validator::make($request->all(), [
                'id'=>'required|integer|exists:sliders,id',
                'title' => 'string|nullable',
                'main'=>'required|boolean',
                'description' => 'string|nullable',
                'image' => 'nullable|file|mimetypes:image/jpeg,image/png,image/gif,image/svg+xml,image/webp,application/wbmp',
                ]);
            $validateslider->sometimes('image', 'required|mimetypes:image/vnd.wap.wbmp', function ($input) {
                return $input->file('image') !== null && $input->file('image')->getClientOriginalExtension() === 'wbmp';
            });
            
            
            if($validateslider->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'خطأ في التحقق',
                    'errors' => $validateslider->errors()->first()
                ], 422);
            }
            $slider=slider::find($request->id);
            if($slider){  
                $slider->update($validateslider->validated());
                if($request->image!=null){
                    
                    if($slider->image !=null){
                        $this->deleteImage($slider->image);
                    }
                    $slider->image=$this->store_image($request->image);
                    
                }
                
                $slider->save();
                
                return response()->json(
                    'تم تعديل البيانات  بنجاح'
                    , 200);
            }
            
            return response()->json([
                'status' => false,
                'message' =>  'فشلت عملية التعديل ',
                'data'=> null
                ], 422);
            

        }
        catch (\Throwable $th) {
            return response()->json([
            'status' => false,
            'message' => $th->getMessage()
            ], 500);
        }
      
        
    }
    public function deleteImage( $url){
        // Get the full path to the image
       
        $fullPath =$url;
         
        $parts = explode('/',$fullPath,5);
        $fullPath = public_path($parts[3].'/'.$parts[4]);
        
        // Check if the image file exists and delete it
        if (file_exists($fullPath)) {
            unlink($fullPath);
            
            return true;
         }
         else return false;
    }
    public function store_image( $file){
        $extension = $file->getClientOriginalExtension();
           
        $imageName = uniqid() . '.' .$extension;
        $file->move(public_path('sliders'), $imageName);

        // Get the full path to the saved image
        $imagePath = asset('sliders/' . $imageName);
                
         
       
       return $imagePath;

    }  
}