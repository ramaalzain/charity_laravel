<?php

namespace App\Http\Controllers;

use App\Models\ProjectType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class ProjectTypeController extends Controller
{
    public function index(){
        $ProjectTypes=ProjectType::get();
        return response()->json(
            $ProjectTypes
            ,200);
    }
    public function store(Request $request){
        
        try{
            
              
            $validateProjectType = Validator::make($request->all(), 
            [
               'name' => 'string|required',
       
            ]);
           

            if($validateProjectType->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'خطأ في التحقق',
                    'errors' => $validateProjectType->errors()
                ], 422);
            }

            $ProjectType = ProjectType::create(array_merge(
                $validateProjectType->validated()
                
                ));
            
        
            $result=$ProjectType->save();
           if ($result){
               
                return response()->json(
                 'تم أضافة بيانات نوع المشروع بنجاح'
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
    public function destroy($id){
        try {  
             
            $input = [ 'id' =>$id ];
            $validate = Validator::make( $input,
                ['id'=>'required|integer|exists:Project_types,id']);
            if($validate->fails()){
            return response()->json([
               'status' => false,
               'message' => 'خطأ في التحقق',
               'errors' => $validate->errors()
            ], 422);}
          
            $ProjectType=ProjectType::find($id);
         
           
          if($ProjectType){ 
               
                $projects= $ProjectType->projects()->get();
                 
                foreach($projects as $project){
                    $project->projectType()->dissociate();
                    $project->save();
                }
                $result= $ProjectType->delete();
                if($result){ 
                    return response()->json(
                    ' تم حذف بيانات نوع المشروع بنجاح'
                    , 200);
                }
            }

            return response()->json(null, 422);
        }
        catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } 
        catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while deleting the ProjectType.'], 500);
        }
    }
    public function update(Request $request, $id){
        try{
            $input = [ 'id' =>$id ];
            $validate = Validator::make( $input,
            ['id'=>'required|integer|exists:Project_types,id']);
            if($validate->fails()){
                    return response()->json([
                        'status' => false,
                        'message' => 'خطأ في التحقق',
                        'errors' => $validate->errors()
                    ], 422);
                }
                
            $ProjectType=ProjectType::find($id);
            
            $validateProjectType = Validator::make($request->all(), [
                'name' => 'string|nullable',
              ]);
            
            
            if($validateProjectType->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'خطأ في التحقق',
                    'errors' => $validateProjectType->errors()
                ], 422);
            }
            if($ProjectType){  
                $ProjectType->update($validateProjectType->validated());
                
                $ProjectType->save();
                
                return response()->json(
                    'تم تعديل بيانات نوع المشروع بنجاح'
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
        $file->move(public_path('ProjectTypes'), $imageName);

        // Get the full path to the saved image
        $imagePath = asset('ProjectTypes/' . $imageName);
                
         
       
       return $imagePath;

    }
}